<?php

namespace Own3d\Id\Http\Middleware;

use Own3d\Id\Contracts\ScopeAuthorizable;
use Own3d\Id\Exceptions\MissingScopeException;

class CheckToken extends ValidateToken
{
    /**
     * Determine if the token has all the given scopes.
     *
     * @throws MissingScopeException
     */
    protected function validate(ScopeAuthorizable $token, string ...$params): void
    {
        foreach ($params as $scope) {
            if ($token->cant($scope)) {
                throw new MissingScopeException($scope);
            }
        }
    }
}
