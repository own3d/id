<?php

namespace Own3d\Id\ApiOperations;

use Own3d\Id\Helpers\Paginator;
use Own3d\Id\Result;

/**
 * @author René Preuß <rene.p@own3d.tv>
 */
trait Get
{
    abstract public function get(string $path = '', array $parameters = [], Paginator $paginator = null): Result;
}
