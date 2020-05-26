<?php

declare(strict_types=1);

namespace Own3d\Id\Tests\TestCases;

use Own3d\Id\Own3dId;
use Own3d\Id\Providers\Own3dIdServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

/**
 * @author René Preuß <rene@preuss.io>
 */
abstract class TestCase extends BaseTestCase
{

    protected function getPackageProviders($app)
    {
        return [
            Own3dIdServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Own3dId' => Own3dId::class,
        ];
    }
}