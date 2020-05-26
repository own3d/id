<?php

return [
    'client_id'     => env('BITINFLOW_ACCOUNTS_KEY', ''),
    'client_secret' => env('BITINFLOW_ACCOUNTS_SECRET', ''),
    'redirect_url'  => env('BITINFLOW_ACCOUNTS_REDIRECT_URI', ''),
    'base_url'      => env('BITINFLOW_ACCOUNTS_BASE_URI', ''),
    'model'         => env('BITINFLOW_ACCOUNTS_MODEL', \Illuminate\Foundation\Auth\User::class),
];