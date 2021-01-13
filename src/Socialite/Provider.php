<?php

namespace Own3d\Id\Socialite;

namespace Own3d\Id\Socialite;

use Illuminate\Support\Arr;
use Laravel\Socialite\Two\ProviderInterface;
use Own3d\Id\Enums\Scope;
use SocialiteProviders\Manager\OAuth2\AbstractProvider;
use SocialiteProviders\Manager\OAuth2\User;

/**
 * @author René Preuß <rene.p@own3d.tv>
 */
class Provider extends AbstractProvider implements ProviderInterface
{

    /**
     * Unique Provider Identifier.
     */
    const IDENTIFIER = 'own3d-id';

    /**
     * {@inheritdoc}
     */
    protected $scopes = [Scope::USER_READ, Scope::CONNECTIONS];

    /**
     * {@inherticdoc}.
     */
    protected $scopeSeparator = ' ';

    /**
     * {@inheritdoc}
     */
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase(
            'https://id.stream.tv/oauth/authorize', $state
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenUrl()
    {
        return 'https://id.stream.tv/oauth/token';
    }

    /**
     * {@inheritdoc}
     */
    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->get(
            'https://id.stream.tv/api/users', [
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $token,
            ],
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * {@inheritdoc}
     */
    protected function mapUserToObject(array $user)
    {
        return (new User())->setRaw($user)->map([
            'id' => $user['id'],
            'nickname' => $user['name'],
            'name' => $user['name'],
            'email' => Arr::get($user, 'email'),
            'avatar' => $user['avatar'] ?? null,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenFields($code)
    {
        return array_merge(parent::getTokenFields($code), [
            'grant_type' => 'authorization_code',
        ]);
    }
}