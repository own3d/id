<?php

declare(strict_types=1);

namespace Own3d\Id\Tests\TestCases;

use Own3d\Id\Own3dId;
use Own3d\Id\Result;

/**
 * @author René Preuß <rene@preuss.io>
 */
abstract class ApiTestCase extends TestCase
{
    protected static $rateLimitRemaining = null;

    protected function setUp(): void
    {
        parent::setUp();

        if ($this->getBaseUrl()) {
            Own3dId::setBaseUrl($this->getBaseUrl());
        }

        if ( ! $this->getClientId()) {
            $this->markTestSkipped('No Client-ID given');
        }
        if (null !== self::$rateLimitRemaining && self::$rateLimitRemaining < 3) {
            $this->markTestSkipped('Rate Limit exceeded (' . self::$rateLimitRemaining . ')');
        }
        $this->getClient()->setClientId($this->getClientId());
    }

    protected function registerResult(Result $result): Result
    {
        self::$rateLimitRemaining = $result->rateLimit('remaining');

        return $result;
    }

    protected function getBaseUrl()
    {
        return getenv('BASE_URL');
    }

    protected function getClientId()
    {
        return getenv('CLIENT_ID');
    }

    protected function getClientSecret()
    {
        return getenv('CLIENT_KEY');
    }

    protected function getToken()
    {
        return getenv('CLIENT_ACCESS_TOKEN');
    }

    public function getClient(): Own3dId
    {
        return app(Own3dId::class);
    }
}
