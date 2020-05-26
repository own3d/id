<?php

return [
    'client_id'     => env('OWN3D_ID_KEY', ''),
    'client_secret' => env('OWN3D_ID_SECRET', ''),
    'redirect_url'  => env('OWN3D_ID_REDIRECT_URI', ''),
    'base_url'      => env('OWN3D_ID_BASE_URI', ''),
    'model'         => env('OWN3D_ID_MODEL', \Illuminate\Foundation\Auth\User::class),
];