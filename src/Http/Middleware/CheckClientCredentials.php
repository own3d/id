<?php

namespace Own3d\Id\Http\Middleware;

use Closure;
use Firebase\JWT\JWT;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Own3d\Id\Exceptions\MissingScopeException;
use stdClass;
use Throwable;

class CheckClientCredentials
{
    public const ALLOWED_ALGORITHMS = ['RS256'];

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param mixed ...$scopes
     *
     * @throws AuthenticationException|MissingScopeException
     *
     * @return mixed
     */
    public function handle($request, Closure $next, ...$scopes)
    {
        JWT::$leeway = 60;

        try {
            $decoded = JWT::decode(
                $request->bearerToken(),
                $this->getOauthPublicKey(),
                self::ALLOWED_ALGORITHMS
            );
        } catch (Throwable $exception) {
            throw new AuthenticationException();
        }

        $request->attributes->set('oauth_access_token_id', $decoded->jti);
        $request->attributes->set('oauth_client_id', $decoded->aud);
        $request->attributes->set('oauth_user_id', $decoded->sub);
        $request->attributes->set('oauth_scopes', $decoded->scopes);

        $this->validateScopes($decoded, $scopes);

        return $next($request);
    }

    private function getOauthPublicKey()
    {
        return file_get_contents(__DIR__ . '/../../../oauth-public.key');
    }

    /**
     * Validate token credentials.
     *
     * @param stdClass $token
     * @param array $scopes
     *
     * @throws MissingScopeException
     *
     * @return void
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
