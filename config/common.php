<?php
return [
    'imagesSizes' => [
        ['name' => 'original', 'quality' => 100, 'convertable' => false],
        ['name' => 'medium', 'quality' => 50, 'convertable' => true],
        ['name' => 'thumbnail', 'quality' => 10, 'convertable' => true]
    ],
    'quotation_expiration_period'=>14,
    'first_user_username'=>env('FIRST_USER_USERNAME'),
    'first_user_password'=>env('FIRST_USER_PASSWORD'),
    'generic_error'=>'error_message'
];
