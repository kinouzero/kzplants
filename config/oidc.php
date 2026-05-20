<?php

return [
    'enabled' => env('OIDC_ENABLED', false),
    'issuer' => env('OIDC_ISSUER'),
    'client_id' => env('OIDC_CLIENT_ID'),
    'client_secret' => env('OIDC_CLIENT_SECRET'),
    'redirect_uri' => env('OIDC_REDIRECT_URI'),
    'scopes' => env('OIDC_SCOPES', 'openid email profile'),
    'email_claim' => env('OIDC_EMAIL_CLAIM', 'email'),
    'name_claim' => env('OIDC_NAME_CLAIM', 'name'),
    'groups_claim' => env('OIDC_GROUPS_CLAIM', 'groups'),
    'admin_group' => env('OIDC_ADMIN_GROUP'),
];
