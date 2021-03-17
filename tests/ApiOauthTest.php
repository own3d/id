<?php

declare(strict_types=1);

namespace Own3d\Id\Tests;

use Own3d\Id\Contracts;
use Own3d\Id\Repository\AppTokenRepository;
use Own3d\Id\Tests\TestCases\ApiTestCase;

/**
 * @author René Preuß <rene.p@own3d.tv>
 */
class ApiOauthTest extends ApiTestCase
{
    public function testGetOauthToken(): void
    {
        $this->registerResult($result = $this->getClient()->retrievingToken('client_credentials', [
            'scope' => '',
        ]));
        $this->assertTrue($result->success());
        $this->assertNotEmpty($result->data()->access_token);
    }

    public function testAppTokenRepository(): void
    {
        $repository = app(Contracts\AppTokenRepository::class);

        self::assertInstanceOf(AppTokenRepository::class, $repository);
    }
}
