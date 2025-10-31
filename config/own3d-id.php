<?php

return [
    'client_id' => env('OWN3D_ID_KEY', ''),
    'client_secret' => env('OWN3D_ID_SECRET', ''),
    'redirect_url' => env('OWN3D_ID_REDIRECT_URI', ''),
    'base_url' => env('OWN3D_ID_BASE_URI', ''),
    'token_type' => env('OWN3D_ID_TOKEN_TYPE', 'Bearer'),
    'model' => env('OWN3D_ID_MODEL', \App\Models\User::class),
    'model_key' => env('OWN3D_ID_MODEL_KEY', 'own3d_id'),
    'webhook_shared_secret' => env('OWN3D_ID_WEBHOOK_SHARED_SECRET', ''),
    'webhook_age_tolerance' => intval(env('OWN3D_ID_WEBHOOK_AGE_TOLERANCE', 300)),
];
