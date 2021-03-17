<?php

namespace Own3d\Id\Contracts;

use Own3d\Id\Exceptions\RequestFreshAccessTokenException;

interface AppTokenRepository
{
    /**
     * @throws RequestFreshAccessTokenException
     *
     * @return string
     */
    public function getAccessToken(): string;
}
