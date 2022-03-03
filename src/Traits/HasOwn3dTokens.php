<?php

namespace Own3d\Id\Traits;

use stdClass;

/**
 * @author René Preuß <rene.p@own3d.tv>
 */
trait HasOwn3dTokens
{
    /**
     * The current access token for the authentication user.
     */
    protected ?stdClass $accessToken;

    /**
     * Get the current access token being used by the user.
     */
    public function own3dToken(): ?stdClass
    {
        return $this->accessToken;
    }

    /**
     * Determine if the current API token has a given scope.
     */
    public function own3dTokenCan(string $scope): bool
    {
        $scopes = $this->own3dTokenScopes();

        return in_array('*', $scopes) || in_array($scope, $scopes);
    }

    /**
     * List of scopes the current access token has.
     */
    public function own3dTokenScopes(): array
    {
        return $this->accessToken ? $this->accessToken->scopes : [];
    }

    /**
     * Set the current access token for the user.
     */
    public function withOwn3dAccessToken(stdClass $accessToken): self
    {
        $this->accessToken = $accessToken;

        return $this;
    }
}
