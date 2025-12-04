<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Hash Driver
    |--------------------------------------------------------------------------
    |
    | This option controls the default hash driver that will be used to
    | hash passwords for your application. By default, the bcrypt algorithm
    | is used; however, you remain free to modify this option if you wish.
    |
    | Supported: "bcrypt", "argon", "argon2id"
    |
    */

    'default' => env('HASH_DRIVER', 'bcrypt'),

    /*
    |--------------------------------------------------------------------------
    | Bcrypt Options
    |--------------------------------------------------------------------------
    |
    | Here you may specify the configuration options that should be used when
    | hashing passwords using the Bcrypt algorithm. This will allow you to
    | control the amount of time it takes to hash the given password.
    |
    */

    'bcrypt' => [
        'rounds' => env('BCRYPT_ROUNDS', 10),
    ],

    /*
    |--------------------------------------------------------------------------
    | Argon Options
    |--------------------------------------------------------------------------
    |
    | Here you may specify the configuration options that should be used when
    | hashing passwords using the Argon2 algorithm. These will let you fine-
    | tune the memory consumption and time that is needed to hash the password.
    |
    */

    'argon' => [
        'memory'  => 1024,
        'threads' => 2,
        'time'    => 2,
    ],

];
