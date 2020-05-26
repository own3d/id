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
     * Get currently authed user with Bearer Token
     *
     * @return Result Result object
     */
    public function getAuthedUser(): Result
    {
        return $this->get('users');
    }

    /**
     * Returns a data array with multiple oauth connections.
     *
     * @return Result Result object
     */
    public function getUserConnections(): Result
    {
        return $this->get('linked-social-accounts');
    }
}