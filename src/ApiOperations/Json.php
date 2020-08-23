<?php

namespace Own3d\Id\ApiOperations;

use Own3d\Id\Result;

/**
 * @author René Preuß <rene.p@own3d.tv>
 */
trait Json
{
    abstract public function json(string $method, string $path = '', array $body = null): Result;
}
