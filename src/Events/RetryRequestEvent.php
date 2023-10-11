<?php

namespace PersistentRequest\Events;

use PersistentRequest\DTO\RequestDTO;

/**
 * Event for retry request
 */
class RetryRequestEvent
{
    protected RequestDTO $requestDTO;
    
    public function __construct(RequestDTO $requestDTO)
    {
        $this->requestDTO = $requestDTO;
    }
    
    public function getRequestDTO(): RequestDTO
    {
        return $this->requestDTO;
    }
}
