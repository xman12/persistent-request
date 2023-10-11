<?php

namespace PersistentRequest\DTO;

use Psr\Http\Message\RequestInterface;
use Laravel\SerializableClosure\SerializableClosure;

class RequestDTO
{
    protected RequestInterface $request;
    /** @var null|?\Closure|SerializableClosure */
    protected $extendedLogic;

    /** @var string Event name which dispaching when get success result */
    protected string $event;

    /** @var int|null Max try send request */
    protected ?int $maxAttemps = null;

    /** @var int Number of attempts */
    protected int $attemps = 0;

    /** @var int  */
    protected int $sleepSecond = 60;

    /** @var \Throwable|null  */
    protected ?\Throwable $exception = null;

    /** @var int Current time */
    protected int $currentTime;

    public function __construct(
        RequestInterface $request,
        string $event,
        int $sleepSecond,
        $extendedLogic = null,
        $maxAttemps = null
    )
    {
        $this->request = $request;
        $this->event = $event;
        $this->sleepSecond = $sleepSecond;
        $this->maxAttemps = $maxAttemps;
        $this->extendedLogic = $extendedLogic;
        $this->currentTime = time();
    }
    
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
    
    public function getRequest(): RequestInterface
    {
        return $this->request;
    }
    
    public function getMaxAttemps(): ?int
    {
        return $this->maxAttemps;
    }
    
    public function getSleepSecond(): int
    {
        return $this->sleepSecond;
    }
    
    public function setAttemps(int $attemps): self
    {
        $this->attemps = $attemps;
        
        return $this;
    }
    
    public function getAttemps(): int
    {
        return $this->attemps;
    }
    
    public function setException(?\Throwable $exception): self
    {
        $this->exception = $exception;
        
        return $this;
    }
    
    public function getException(): ?\Throwable
    {
        return $this->exception;
    }
    
    public function getCurrentTime(): int
    {
        return $this->currentTime;
    }
    
    public function setCurrentTime(int $currentTime): self
    {
        $this->currentTime = $currentTime;
        
        return $this;
    }

    public function __serialize(): array
    {
        if (null !== $this->extendedLogic) {
            if ($this->extendedLogic instanceof \Closure) {
                $this->extendedLogic = (new SerializableClosure($this->extendedLogic));
            }
        }
        
        return [
            'request' => $this->request,
            'event' => $this->event,
            'extendedLogic' => $this->extendedLogic,
            'maxAttemps' => $this->maxAttemps,
            'attemps' => $this->attemps,
            'sleepSecond' => $this->sleepSecond,
            'currentTime' => $this->currentTime,
        ];
    }
}