<?php

namespace Own3d\Id\Contracts;

use Illuminate\Contracts\Auth\Authenticatable;

interface OAuthenticatable extends Authenticatable
{
    /**
     * Get the access token currently associated with the user.
     */
    public function currentAccessToken(): ?ScopeAuthorizable;

    /**
     * Set the current access token for the user.
     */
    public function withAccessToken(?ScopeAuthorizable $accessToken): static;
}
