<?php

declare(strict_types=1);

namespace Own3d\Id\Tests;

use Own3d\Id\Tests\TestCases\ApiTestCase;

/**
 * @author René Preuß <rene.p@own3d.tv>
 */
class ApiUsersTest extends ApiTestCase
{
    public function testGetAuthedUser(): void
    {
        $this->getClient()->withToken($this->getToken());
        $this->registerResult($result = $this->getClient()->getAuthedUser());
        $this->assertTrue($result->success());
        $this->assertEquals('rene@bitinflow.com', $result->data()->email);
    }

    public function testGetUserConnections(): void
    {
        $this->getClient()->withToken($this->getToken());
        $this->registerResult($result = $this->getClient()->getUserConnections());
        $this->assertTrue($result->success());
        $this->assertCount(4, $result->data());
        $this->assertEquals('twitch', $result->data()[0]->platform);
    }

    public function testGetUserConnectionByName(): void
    {
        $this->getClient()->withToken($this->getToken());
        $this->registerResult($result = $this->getClient()->getUserConnections(['platform' => 'slack']));
        $this->assertTrue($result->success());
        $this->assertCount(1, $result->data());
        $this->assertEquals('slack', $result->shift()->platform);
    }

    public function testGetUserConnectionByPlatformId(): void
    {
        $this->getClient()->withToken($this->getToken());
        $this->registerResult($result = $this->getClient()->getUserConnectionByPlatformId('twitch', '106415581'));
        $this->assertTrue($result->success());
        $this->assertEquals('twitch', $result->data()->platform);
    }

    public function testGetUserByIdWithAccess(): void
    {
        $this->getClient()->withToken($this->getAppAccessToken(true));
        $this->registerResult($result = $this->getClient()->getUserById('1'));
        $this->assertTrue($result->success());
        $this->assertObjectHasAttribute('email', $result->data());
        $this->assertEquals('rene@preuss.io', $result->data()->email);
    }

    public function testGetUserByIdWithoutAccess(): void
    {
        $this->getClient()->withToken($this->getAppAccessToken(false));
        $this->registerResult($result = $this->getClient()->getUserById('1'));
        $this->assertTrue($result->success());
        $this->assertObjectNotHasAttribute('email', $result->data());
    }

    private function getAppAccessToken(bool $trusted): string
    {
        if ($trusted) {
            $this->getClient()->withClientId($this->getTrustedClientId());
            $this->getClient()->withClientSecret($this->getTrustedClientSecret());
        } else {
            $this->getClient()->withClientId($this->getClientId());
            $this->getClient()->withClientSecret($this->getClientSecret());
        }


        $result = $this->getClient()->retrievingToken('client_credentials', [
            'scope' => '*',
        ]);

        return $result->data()->access_token;
    }
}
