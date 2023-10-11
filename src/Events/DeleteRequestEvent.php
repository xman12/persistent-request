<?php

namespace PersistentRequest\Events;

use PersistentRequest\DTO\RequestDTO;
use Psr\Http\Message\ResponseInterface;

final class DeleteRequestEvent
{
    protected RequestDTO $requestDTO;

    public function __construct(RequestDTO $requestDTO)
    {
        $this->requestDTO = $requestDTO;
    }

    /**
     * @return RequestDTO
     */
    public function getRequestDTO(): RequestDTO
    {
        return $this->requestDTO;
    }
}