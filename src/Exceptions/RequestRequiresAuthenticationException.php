<?php

namespace Own3d\Id\Exceptions;

use Exception;

/**
 * @author René Preuß <rene.p@own3d.tv>
 */
class RequestRequiresAuthenticationException extends Exception
{
    public function __construct($message = 'Request requires authentication', $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
