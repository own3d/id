<?php

namespace Own3d\Id\Http\Middleware;

use Own3d\Id\Contracts\ScopeAuthorizable;
use Own3d\Id\Exceptions\MissingScopeException;

class CheckTokenForAnyScope extends ValidateToken
{
    /**
     * Determine if the token has at least one of the given scopes.
     *
     * @throws MissingScopeException
     */
    protected function validate(ScopeAuthorizable $token, string ...$params): void
    {
        foreach ($params as $scope) {
            if ($token->can($scope)) {
                return;
            }
        }

        throw new MissingScopeException($params);
    }
}
