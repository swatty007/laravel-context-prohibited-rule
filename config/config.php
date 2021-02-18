<?php

/*
 * You can place your custom package configuration in here.
 */
return [
    /*
        |--------------------------------------------------------------------------
        | Allow Application Name
        |--------------------------------------------------------------------------
        |
        | This option defines, if your application name is allowed to pass our validation rule.
        |
        */

    'allow_application_name' => false,

    /*
        |--------------------------------------------------------------------------
        | Allow Hostname
        |--------------------------------------------------------------------------
        |
        | This option defines, if your applications hostname is allowed to pass our validation rule.
        |
        */

    'allow_hostname' => false,

    /*
        |--------------------------------------------------------------------------
        | Min Length
        |--------------------------------------------------------------------------
        |
        | This option defines the min length a word needs to have for our rule to take effect
        |
        */

    'min_length' => 3,

    /*
        |--------------------------------------------------------------------------
        | Prohibited Words
        |--------------------------------------------------------------------------
        |
        | This option allows you to define a set of default words,
        | which should be disallowed.
        |
        */

    'prohibited_words' => [

    ],
];
