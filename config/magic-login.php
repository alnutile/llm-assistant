<?php

use App\Models\User;

return [

    'user_table' => User::class,
    'redirect' => env('MAGIC_LOGIN_REDIRECT', 'dashboard'),
];
