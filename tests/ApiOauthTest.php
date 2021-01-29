<?php

declare(strict_types=1);

namespace Own3d\Id\Tests;

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
}
