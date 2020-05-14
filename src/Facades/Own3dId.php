<?php

namespace Own3d\Id\Facades;

use Own3d\Id\Own3dId as Own3dIdService;
use Illuminate\Support\Facades\Facade;

/**
 * @author René Preuß <rene.p@own3d.tv>
 */
class Own3dId extends Facade
{
    protected static function getFacadeAccessor()
    {
        return Own3dIdService::class;
    }
}