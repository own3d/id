<?php

namespace Own3d\Id\Repository;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Cache;
use JsonException;
use Own3d\Id\Exceptions\RequestFreshAccessTokenException;

/**
 * Helper class to generate a fresh machine-to-machine access token.
 *
 * @author René Preuß <rene.p@own3d.tv>
 */
class AppTokenRepository
{
    public const ACCESS_TOKEN_CACHE_KEY = 'own3d-id:access_token';

    /**
     * @var Client
     */
    private Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://id.own3d.tv/',
        ]);
    }

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
     * @throws GuzzleException
     * @throws RequestFreshAccessTokenException
     * @throws JsonException
     *
     * @return mixed
     */
    private function requestFreshAccessToken(string $scope)
    {
        $response = $this->getClient()->post('oauth/token', [
            'form_params' => [
                'grant_type' => 'client_credentials',
                'client_id' => config('own3d-id.client_id'),
                'client_secret' => config('own3d-id.client_secret'),
                'scope' => $scope,
            ],
        ]);

        if (200 !== $response->getStatusCode()) {
            throw RequestFreshAccessTokenException::fromResponse($response);
        }

        $response = json_decode((string) $response->getBody(), true, 512, JSON_THROW_ON_ERROR);

        Cache::put(self::ACCESS_TOKEN_CACHE_KEY, $response['access_token'], now()->addWeek());

        return $response['access_token'];
    }

    private function getClient(): Client
    {
        return $this->client;
    }
}
