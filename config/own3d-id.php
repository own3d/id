<?php

return [
    'client_id' => env('OWN3D_ID_KEY', ''),
    'client_secret' => env('OWN3D_ID_SECRET', ''),
    'redirect_url' => env('OWN3D_ID_REDIRECT_URI', ''),
    'base_url' => env('OWN3D_ID_BASE_URI', ''),
    'token_type' => env('OWN3D_ID_TOKEN_TYPE', 'Bearer'),
    'model_key' => env('OWN3D_ID_MODEL_KEY', 'own3d_id'),
    'webhook_shared_secret' => env('OWN3D_ID_WEBHOOK_SHARED_SECRET', ''),
    'webhook_age_tolerance' => intval(env('OWN3D_ID_WEBHOOK_AGE_TOLERANCE', 300)),


    /*
     * --------------------------------------------------------------------------
     * JWT Authentication
     * --------------------------------------------------------------------------
     *
     * These options configure the JWT authentication for StreamTV ID.
     *
     */
    'jwks_uri' => env('OWN3D_ID_JWKS_URI', 'https://id.stream.tv/.well-known/jwks.json'),
    'allowed_algs' => array_values(array_filter(array_map('trim', explode(',', env('OWN3D_ID_ALLOWED_ALGS', 'RS256,PS256,ES256'))))),
    'leeway' => (int) env('OWN3D_ID_JWT_LEEWAY', 60),
    'jwks_ttl' => (int) env('OWN3D_ID_JWKS_TTL', 600),
];
