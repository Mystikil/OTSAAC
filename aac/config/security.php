<?php
return [
    'hash' => [
        'memory_cost' => PASSWORD_ARGON2_DEFAULT_MEMORY_COST,
        'time_cost' => PASSWORD_ARGON2_DEFAULT_TIME_COST,
        'threads' => PASSWORD_ARGON2_DEFAULT_THREADS,
    ],
    'csrf' => true,
    'captcha' => [
        'enabled' => false,
        'provider' => 'hcaptcha',
        'site_key' => '',
        'secret_key' => '',
    ],
    'rate_limit' => [
        'login' => ['max_attempts' => 5, 'window' => 300],
    ],
];
