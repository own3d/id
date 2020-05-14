<?php

declare(strict_types=1);

namespace Own3d\Id\Exceptions;

use Exception;

/**
 * @author René Preuß <rene.p@own3d.tv>
 */
class RequestRequiresRedirectUriException extends Exception
{
    public function __construct($message = 'Request requires redirect uri', $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}