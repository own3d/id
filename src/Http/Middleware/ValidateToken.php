<?php

namespace Own3d\Id\Http\Middleware;

use Own3d\Id\Auth\AccessToken;
use Own3d\Id\Contracts\ScopeAuthorizable;
use Own3d\Id\Exceptions\AuthenticationException;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class ValidateToken
{
    /**
     * Specify the parameters for the middleware.
     *
     * @param string[]|string $param
     */
    public static function using(array|string $param, string ...$params): string
    {
        if (is_array($param)) {
            return static::class . ':' . implode(',', $param);
        }

        return static::class . ':' . implode(',', [$param, ...$params]);
    }

    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     *
     * @throws AuthenticationException
     */
    public function handle(Request $request, Closure $next, string ...$params): Response
    {
        $token = $this->validateToken($request);

        $this->validate($token, ...$params);

        return $next($request);
    }

    /**
     * Validate and get the request's access token.
     *
     * @throws AuthenticationException
     */
    protected function validateToken(Request $request): ScopeAuthorizable
    {
        // If the user is authenticated and already has an access token set via
        // the token guard, there's no need to validate the request's bearer
        // token again, so we'll return the access token as the valid one.
        if ($request->user()?->currentAccessToken()) {
            return $request->user()->currentAccessToken();
        }

        // Otherwise, we'll validate the bearer token from the request.
        return AccessToken::fromRequest($request);
    }

    /**
     * Validate the given access token.
     */
    abstract protected function validate(ScopeAuthorizable $token, string ...$params): void;
}
