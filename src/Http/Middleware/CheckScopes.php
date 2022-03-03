<?php

namespace Own3d\Id\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Own3d\Id\Exceptions\MissingScopeException;

/**
 * @author René Preuß <rene.p@own3d.tv>
 */
class CheckScopes
{
    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param mixed ...$scopes
     * @return Response
     *
     * @throws AuthenticationException|MissingScopeException
     */
    public function handle($request, $next, ...$scopes)
    {
        if (!$request->user() || !$request->user()->own3dToken()) {
            throw new AuthenticationException;
        }

        foreach ($scopes as $scope) {
            if (!$request->user()->own3dTokenCan($scope)) {
                throw new MissingScopeException(
                    $scope,
                    'Invalid scope(s) provided.',
                    $scopes,
                    MissingScopeException::CONDITION_ALL
                );
            }
        }

        return $next($request);
    }
}
