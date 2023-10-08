<?php

namespace PersistentRequest\Events;

use Psr\Http\Message\ResponseInterface;

final class SuccessEvent
{
    protected ResponseInterface $response;
    
    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }

    /**
     * @return ResponseInterface
     */
    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }
}