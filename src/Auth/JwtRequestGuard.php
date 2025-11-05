<?php

namespace Own3d\Id\Auth;

use Exception;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Own3d\Id\Own3dId;

/**
 * Encapsulates JWT request-based authentication used by Auth::viaRequest.
 */
class JwtRequestGuard
{
    /**
     * Attempt to authenticate the request and return a User or null.
     *
     * @param Request $request
     * @return Authenticatable|null
     */
    public function authenticate(Request $request): ?Authenticatable
    {
        try {
            $accessToken = AccessToken::fromRequest($request);
        } catch (Exception) {
            return null;
        }

        if (empty($accessToken->claims->sub)) {
            return null;
        }

        $user = Own3dId::user()->query()->whereKey($accessToken->claims->sub)->firstOr(['*'], function () use ($accessToken) {
            return Own3dId::user()->query()->create([
                'id' => $accessToken->claims->sub,
            ]);
        });

        // Set user on the api guard
        Auth::guard('api')->setUser($user);

        // Optionally, still set the user resolver for request
        $request->setUserResolver(fn() => $user);

        return $user->withAccessToken($accessToken);
    }
}

