<?php

namespace Own3d\Id\Traits;

use GuzzleHttp\Exception\GuzzleException;
use Own3d\Id\ApiOperations\Get;
use Own3d\Id\Exceptions\RequestRequiresClientIdException;
use Own3d\Id\Result;

/**
 * @author René Preuß <rene.p@own3d.tv>
 */
trait UsersTrait
{
    use Get;

    /**
     * Get currently authed user with Bearer Token.
     *
     * @throws GuzzleException
     * @throws RequestRequiresClientIdException
     */
    public function getAuthedUser(): Result
    {
        return $this->get('users');
    }

    /**
     * Get user by ID.
     *
     * @throws GuzzleException
     * @throws RequestRequiresClientIdException
     */
    public function getUserById(string $id): Result
    {
        return $this->get(sprintf('users/%s', $id));
    }

    /**
     * Returns a data array. Users may use the same email.
     *
     * This endpoint is only available for trusted first-party clients.
     *
     * @throws GuzzleException
     * @throws RequestRequiresClientIdException
     */
    public function getUsersByEmail(string $email): Result
    {
        return $this->post('users/lookup', [
            'email' => $email,
        ]);
    }

    /**
     * Returns a data array with multiple oauth connections.
     *
     * @throws GuzzleException
     * @throws RequestRequiresClientIdException
     */
    public function getUserConnections(array $parameters = []): Result
    {
        return $this->get('linked-social-accounts', $parameters);
    }

    /**
     * Returns a data array.
     *
     * You can find a list of all supported platforms in the Platform enums class.
     *
     * @throws GuzzleException
     * @throws RequestRequiresClientIdException
     */
    public function getUserConnectionByPlatformId(string $platform, string $id): Result
    {
        return $this->post('linked-social-accounts/lookup', [
            'platform' => $platform,
            'id' => $id,
        ]);
    }
}
