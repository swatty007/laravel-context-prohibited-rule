<?php

namespace Swatty007\LaravelContextProhibitedRule;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Swatty007\LaravelContextProhibitedRule\Rules\ContextProhibited;

class ContextProhibitedServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        Validator::extend('context_prohibited', ContextProhibited::class);

        /*
         * Optional methods to load your package assets
         */
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'context-prohibited-rule');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('context-prohibited-rule.php'),
            ], 'config');
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'context-prohibited-rule');
    }
}
