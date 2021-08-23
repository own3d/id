<?php

namespace Own3d\Id\Http\Middleware;

use Own3d\Id\Exceptions\MissingScopeException;
use stdClass;

class CheckClientCredentialsForAnyScope extends CheckCredentials
{
    /**
     * Validate token credentials.
     *
     * @param stdClass $token
     * @param array $scopes
     *
     * @return void
     * @throws MissingScopeException
     *
     */
    protected function validateScopes(stdClass $token, array $scopes)
    {
        if (in_array('*', $token->scopes)) {
            return;
        }

        foreach ($scopes as $scope) {
            if (in_array($scope, $token->scopes)) {
                return;
            }
        }

        throw new MissingScopeException($scopes);
    }
}
