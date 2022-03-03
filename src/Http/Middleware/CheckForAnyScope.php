<?php

namespace Own3d\Id\Http\Middleware;

use Closure;
use Own3d\Id\Exceptions\MissingScopeException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * @author René Preuß <rene.p@own3d.tv>
 */
class CheckForAnyScope
{
    /**
     * Handle the incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @param  mixed  ...$scopes
     * @return Response
     *
     * @throws AuthenticationException|MissingScopeException
     */
    public function handle($request, $next, ...$scopes)
    {
        if (! $request->user() || ! $request->user()->own3dToken()) {
            throw new AuthenticationException;
        }

        foreach ($scopes as $scope) {
            if ($request->user()->own3dTokenCan($scope)) {
                return $next($request);
            }
        }

        throw new MissingScopeException(
            $scopes,
            'Invalid scope(s) provided.',
            $request->user()->own3dTokenScopes(),
            MissingScopeException::CONDITION_ANY
        );
    }
}
