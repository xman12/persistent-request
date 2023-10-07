<?php

namespace PersistentRequest\DTO;

use Psr\Http\Message\RequestInterface;

class RequestDTO
{
    public ?RequestInterface $request;
    public ?\Closure $extendedLogic;
    public $event;
}