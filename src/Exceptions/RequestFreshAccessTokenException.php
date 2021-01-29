<?php

namespace Own3d\Id\Exceptions;

use DomainException;
use Psr\Http\Message\ResponseInterface;

/**
 * @author René Preuß <rene.p@own3d.tv>
 */
class RequestFreshAccessTokenException extends DomainException
{
    /**
     * @var ResponseInterface
     */
    private ResponseInterface $response;

    public static function fromResponse(ResponseInterface $response): self
    {
        $instance = new self(sprintf('Refresh token request from own3d id failed. Status Code is %s.', $response->getStatusCode()));
        $instance->response = $response;

        return $instance;
    }
}
