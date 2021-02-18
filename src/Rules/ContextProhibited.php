<?php

namespace Swatty007\LaravelContextProhibitedRule\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Str;

class ContextProhibited implements Rule
{
    /**
     * Dataset of context specific words we want to validate against
     *
     * @var array|false|string[]
     */
    protected $prohibitedWords = [];

    /**
     * Create a new rule instance.
     *
     */
    public function __construct()
    {
        // Assemble our set of context specific words
        if (!config('context-prohibited-rule.allow_application_name')) {
            array_push($this->prohibitedWords, Str::lower(Str::camel(config('app.name'))));
        }

        if (!config('context-prohibited-rule.allow_hostname')) {
            $hostname = Str::beforeLast(parse_url(config('app.url'), PHP_URL_HOST), '.');
            array_push($this->prohibitedWords, Str::lower($hostname));
        }

        $this->prohibitedWords = array_merge($this->prohibitedWords, config('context-prohibited-rule.prohibited_words'));

        // Remove and word, which is less then 3 chars from our list again
        // Otherwise this rule will be quite impossible to match...
        foreach ($this->prohibitedWords as $key => $word) {
            if (strlen($word) < config('context-prohibited-rule.min_length')) {
                unset($this->prohibitedWords[$key]);
            }
        }
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        $value = strtolower($value);

        foreach ($this->prohibitedWords as $word) {
            if (stripos($value, $word) !== false) {
                return false;
            }
        }

        return true;
    }


    public function validate($attribute, $value, $params): bool
    {
        return $this->passes($attribute, $value);
    }

    public function message()
    {
        return __(
            'validation.context_prohibited',
            [
                'prohibited' => implode(',', $this->prohibitedWords)
            ]
        );
    }
}
