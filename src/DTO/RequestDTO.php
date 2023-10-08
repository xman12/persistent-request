<?php

namespace PersistentRequest\DTO;

use PersistentRequest\Events\SuccessEvent;
use Psr\Http\Message\RequestInterface;
use Laravel\SerializableClosure\SerializableClosure;

class RequestDTO
{
    public RequestInterface $request;
    /** @var null|?\Closure|SerializableClosure */
    public $extendedLogic;
    public string $event;
    
    public function __construct(RequestInterface $request, string $event, $extendedLogic = null)
    {
        $this->request = $request;
        $this->event = $event;
        $this->extendedLogic = $extendedLogic;
    }

    /**
     * @return string
     */
    public function getEvent(): string
    {
        return $this->event;
    }

    /**
     * @return \Closure|SerializableClosure|null
     */
    public function getExtendedLogic()
    {
        return $this->extendedLogic;
    }

    /**
     * @return RequestInterface
     */
    public function getRequest(): RequestInterface
    {
        return $this->request;
    }
}