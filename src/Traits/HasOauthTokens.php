<?php

namespace Own3d\Id\Traits;

use Own3d\Id\Contracts\ScopeAuthorizable;

trait HasOauthTokens
{
    /**
     * The current access token for the authentication user.
     */
    protected ?ScopeAuthorizable $accessToken = null;


    /**
     * Get the access token currently associated with the user.
     */
    public function currentAccessToken(): ?ScopeAuthorizable
    {
        return $this->accessToken;
    }

    /**
     * Set the current access token for the user.
     */
    public function withAccessToken(?ScopeAuthorizable $accessToken): static
    {
        $this->accessToken = $accessToken;

        return $this;
    }
}
