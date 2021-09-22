<?php

namespace Own3d\Id\Traits;

use Own3d\Id\ApiOperations\Get;
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
     * @return Result Result object
     */
    public function getAuthedUser(): Result
    {
        return $this->get('users');
    }

    /**
     * Get user by ID.
     *
     * @param string $id Platform user id
     *
     * @return Result Result object
     */
    public function getUserById(string $id): Result
    {
        return $this->get(sprintf('users/%s', $id));
    }

    /**
     * Returns a data array with multiple oauth connections.
     *
     * @param array $parameters
     * @return Result Result object
     */
    public function getUserConnections(array $parameters = []): Result
    {
        return $this->get('linked-social-accounts', $parameters);
    }

    /**
     * Returns a data array.
     *
     * @param string $platform Platform slug
     * @param string $id Platform user id
     *
     * @return Result Result object
     */
    public function getUserConnectionByPlatformId(string $platform, string $id): Result
    {
        return $this->post('linked-social-accounts/lookup', [
            'platform' => $platform,
            'id' => $id,
        ]);
    }
}
