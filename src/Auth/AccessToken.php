<?php

namespace Own3d\Id\Auth;

use Own3d\Id\Contracts\ScopeAuthorizable;
use Own3d\Id\Traits\DecodesJwtTokens;

class AccessToken implements ScopeAuthorizable
{
    use DecodesJwtTokens;

    public function __construct(
        public object $claims
    )
    {
    }

    private function extractScopes(object $claims): array
    {
        // OIDC style: space-separated "scope"
        if (isset($claims->scope) && is_string($claims->scope)) {
            return preg_split('/\s+/', trim($claims->scope)) ?: [];
        }
        // Alternate: array claim "scopes"
        if (isset($claims->scopes) && is_array($claims->scopes)) {
            return array_values($claims->scopes);
        }
        return [];
    }

    public function can(string $scope): bool
    {
        if (in_array('*', $this->extractScopes($this->claims), true)) {
            return true;
        }

        $scopes = $this->extractScopes($this->claims);
        return in_array($scope, $scopes, true);
    }

    public function cant(string $scope): bool
    {
        return !$this->can($scope);
    }
}
