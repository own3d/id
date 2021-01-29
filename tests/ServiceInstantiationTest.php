<?php

declare(strict_types=1);

namespace Own3d\Id\Tests;

use Own3d\Id\Facades\Own3dId as Own3dIdFacade;
use Own3d\Id\Own3dId;
use Own3d\Id\Tests\TestCases\TestCase;

/**
 * @author René Preuß <rene.p@own3d.tv>
 */
class ServiceInstantiationTest extends TestCase
{
    public function testInstance(): void
    {
        $this->assertInstanceOf(Own3dId::class, app(Own3dId::class));
    }

    public function testFacade(): void
    {
        $this->assertInstanceOf(Own3dId::class, Own3dIdFacade::getFacadeRoot());
    }
}
