<?php

namespace Own3d\Id\Http\Middleware;

use Closure;
use Own3d\Id\Exceptions\MissingScopeException;
use Own3d\Id\Helpers\JwtParser;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use stdClass;

/**
 * @author René Preuß <rene.p@own3d.tv>
 */
abstract class CheckCredentials
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param mixed ...$scopes
     *
     * @return mixed
     * @throws AuthenticationException|MissingScopeException
     *
     */
    public function handle($request, Closure $next, ...$scopes)
    {
        $decoded = $this->getJwtParser()->decode($request);

        $request->attributes->set('oauth_access_token_id', $decoded->jti);
        $request->attributes->set('oauth_client_id', $decoded->aud);
        $request->attributes->set('oauth_client_trusted', $decoded->client->trusted);
        $request->attributes->set('oauth_user_id', $decoded->sub);
        $request->attributes->set('oauth_scopes', $decoded->scopes);

        $this->validateScopes($decoded, $scopes);

        return $next($request);
    }

    private function getJwtParser(): JwtParser
    {
        return app(JwtParser::class);
    }

    abstract protected function validateScopes(stdClass $token, array $scopes);
}
