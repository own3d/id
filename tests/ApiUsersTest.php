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
}
