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
     *
     * @var stdClass
     */
    protected $accessToken;

    /**
     * Get the current access token being used by the user.
     *
     * @return stdClass|null
     */
    public function own3dToken(): ?stdClass
    {
        return $this->accessToken;
    }

    /**
     * Determine if the current API token has a given scope.
     *
     * @param string $scope
     * @return bool
     */
    public function own3dTokenCan(string $scope): bool
    {
        $scopes = $this->accessToken ? $this->accessToken->scopes : [];

        return in_array('*', $scopes) || in_array($scope, $this->accessToken->scopes);
    }

    /**
     * Set the current access token for the user.
     *
     * @param stdClass $accessToken
     * @return $this
     */
    public function withOwn3dAccessToken(stdClass $accessToken): self
    {
        $this->accessToken = $accessToken;

        return $this;
    }
}
