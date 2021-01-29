<?php

namespace Own3d\Id\Exceptions;

use Exception;

/**
 * @author René Preuß <rene.p@own3d.tv>
 */
class RequestRequiresParameter extends Exception
{
    public function __construct($message = 'Request requires parameters', $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
