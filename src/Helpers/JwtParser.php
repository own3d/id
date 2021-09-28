<?php

namespace Own3d\Id\Helpers;

use Firebase\JWT\JWT;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use stdClass;
use Throwable;

class JwtParser
{
    public const ALLOWED_ALGORITHMS = ['RS256'];

    /**
     * @param Request $request
     * @return stdClass
     * @throws AuthenticationException
     */
    public function decode(Request $request): stdClass
    {
        JWT::$leeway = 60;

        try {
            return JWT::decode(
                $request->bearerToken(),
                $this->getOauthPublicKey(),
                self::ALLOWED_ALGORITHMS
            );
        } catch (Throwable $exception) {
            throw (new AuthenticationException());
        }
    }

    private function getOauthPublicKey()
    {
        return file_get_contents(__DIR__ . '/../../oauth-public.key');
    }
}
