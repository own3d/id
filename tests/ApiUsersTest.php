<?php

declare(strict_types=1);

namespace Own3d\Id\Tests;

use Own3d\Id\Enums\Scope;
use Own3d\Id\Tests\TestCases\ApiTestCase;
use Illuminate\Support\Str;

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
        $this->assertCount(1, $result->data());
        $this->assertEquals('discord', $result->data()[0]->platform);
    }
}
