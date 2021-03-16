<?php

namespace Own3d\Id\Repository;

use Illuminate\Support\Facades\Cache;
use Own3d\Id\Exceptions\RequestFreshAccessTokenException;
use Own3d\Id\Own3dId;

/**
 * Helper class to generate a fresh machine-to-machine access token.
 *
 * @author René Preuß <rene.p@own3d.tv>
 */
class AppTokenRepository
{
    public const ACCESS_TOKEN_CACHE_KEY = 'own3d-id:access_token';

    private Own3dId $client;

    public function __construct()
    {
        $this->client = app(Own3dId::class);
    }

    /**
     * @throws RequestFreshAccessTokenException
     *
     * @return string
     */
    public function getAccessToken(): string
    {
        $accessToken = Cache::get(self::ACCESS_TOKEN_CACHE_KEY);

        if ($accessToken) {
            return $accessToken;
        }

        return $this->requestFreshAccessToken('*');
    }

    /**
     * @param string $scope
     *
     * @throws RequestFreshAccessTokenException
     *
     * @return mixed
     */
    private function requestFreshAccessToken(string $scope)
    {
        $result = $this->getClient()->retrievingToken('client_credentials', [
            'scope' => $scope,
        ]);

        if ( ! $result->success()) {
            throw RequestFreshAccessTokenException::fromResponse($result->response());
        }

        Cache::put(self::ACCESS_TOKEN_CACHE_KEY, $accessToken = $result->data()->access_token, now()->addWeek());

        return $accessToken;
    }

    private function getClient(): Own3dId
    {
        return $this->client;
    }
}
