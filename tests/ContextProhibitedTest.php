<?php

namespace Swatty007\LaravelContextProhibitedRule\Tests;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use Orchestra\Testbench\TestCase;
use Swatty007\LaravelContextProhibitedRule\ContextProhibitedServiceProvider;
use Swatty007\LaravelContextProhibitedRule\Rules\ContextProhibited;

class ContextProhibitedTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Config::set('app.name', 'Amazing App');
        Config::set('app.url', 'https://homestead.test');
    }

    protected function getPackageProviders($app): array
    {
        return [ContextProhibitedServiceProvider::class];
    }

    /**
     * Helper function to avoid duplicate code within our test file
     *
     * @note validation helper
     */
    private function validate_string($input): \Illuminate\Contracts\Validation\Validator
    {
        $data = ['password' => $input];
        return Validator::make($data, [
        // 'password' => 'required|context_prohibited',
        // or
            'password' => ['required', new ContextProhibited],
        ]);
    }

    public function test_valid_data()
    {
        $validator = $this->validate_string('testString');
        $this->assertFalse($validator->fails());
    }

    public function test_invalid_data()
    {
        $validator = $this->validate_string('testHomesteadString');

        $this->assertTrue($validator->fails());
    }

    public function test_message_attributes()
    {
        $validator = $this->validate_string('testHomesteadString');

        $this->assertEquals(
            'validation.context_prohibited',
            $validator->errors()->first('password')
        );
    }

    public function test_application_name_config()
    {
        // Make sure we can set this rule
        Config::set('context-prohibited-rule.allow_application_name', false);
        $validator = $this->validate_string('testAmazingAppString');
        $this->assertTrue($validator->fails());

        // but also ignore it
        Config::set('context-prohibited-rule.allow_application_name', true);
        $validator = $this->validate_string('testAmazingAppString');
        $this->assertFalse($validator->fails());
    }

    public function test_hostname_config()
    {
        // Make sure we can set this rule
        Config::set('context-prohibited-rule.allow_hostname', false);
        $validator = $this->validate_string('testHomesteadString');
        $this->assertTrue($validator->fails());

        // but also ignore it
        Config::set('context-prohibited-rule.allow_hostname', true);
        $validator = $this->validate_string('testHomesteadString');
        $this->assertFalse($validator->fails());
    }

    public function test_min_characters_config()
    {
        Config::set('context-prohibited-rule.prohibited_words', ['foo']);

        // Make sure we can set this rule
        Config::set('context-prohibited-rule.min_length', 4);
        $validator = $this->validate_string('testFooBarString');
        $this->assertFalse($validator->fails());

        // but also ignore it
        Config::set('context-prohibited-rule.min_length', 2);
        $validator = $this->validate_string('testFooBarString');
        $this->assertTrue($validator->fails());
    }

    public function test_prohibited_words_config()
    {
        // Make sure we can set this rule
        Config::set('context-prohibited-rule.prohibited_words', ['cookie']);
        $validator = $this->validate_string('testCookieString');
        $this->assertTrue($validator->fails());

        // but also ignore it
        Config::set('context-prohibited-rule.prohibited_words', []);
        $validator = $this->validate_string('testCookieString');
        $this->assertFalse($validator->fails());
    }
}
