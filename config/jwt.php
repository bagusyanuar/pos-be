<?php

return [
    'issuer' => env('JWT_ISSUER', 'be-pos'),
    'secret' => env('JWT_SECRET', ''),
    'secret_refresh' => env('JWT_SECRET_REFRESH', ''),
    'secret_password_reset' => env('JWT_SECRET_PASSWORD_RESET', ''),
    'exp' => env('JWT_EXPIRATION', 3600),
    'refresh_exp' => env('JWT_REFRESH_EXPIRATION', 30),
    'reset_password_exp' => env('JWT_PASSWORD_RESET_EXPIRATION', 30),
];
