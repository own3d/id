<?php

namespace Own3d\Id\Http\Middleware;

use Closure;
use Own3d\Id\Exceptions\MissingScopeException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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
        if (! $request->user() || ! $request->user()->bitinflowToken()) {
            throw new AuthenticationException;
        }

        foreach ($scopes as $scope) {
            if ($request->user()->bitinflowTokenCan($scope)) {
                return $next($request);
            }
        }

        throw new MissingScopeException($scopes);
    }
}
