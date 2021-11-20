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
        return $this->get('users/@me');
    }

    /**
     * Update a users email address, this will also trigger the email verification process via e-mail.
     * If the email is the same, it will only trigger the email verification process.
     *
     * Email Verification Rules: required, email:rfc,dns
     *
     * @return Result Result object
     */
    public function setAuthedUserEmailAddress(string $email): Result
    {
        return $this->post('users/@me/update-email', [
            'email' => $email,
        ]);
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
