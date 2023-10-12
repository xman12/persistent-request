<?php

namespace PersistentRequest\Services;

use Illuminate\Contracts\Bus\Dispatcher;
use PersistentRequest\DTO\RequestDTO;
use PersistentRequest\Models\RequstModel;

/**
 * Service for execute requests
 */
interface RequestServiceInterface
{
    /**
     * Execute request 
     * 
     * @param RequestDTO $request
     * @return void
     */
    public function execute(RequestDTO $request): bool;

    /**
     * Retry request from database 
     *
     * @param RequestDTO $request
     * @return bool
     */
    public function retryExecute(RequestDTO $request): bool;

    /**
     * Set custom client
     *
     * @param \GuzzleHttp\ClientInterface $client
     * @return $this
     */
    public function setClient(\GuzzleHttp\ClientInterface $client): self;

    /**
     * Set custom dispatcher events
     * 
     * @param \Illuminate\Events\Dispatcher $dispatcher
     * @return $this
     */
    public function setDispatcher(\Illuminate\Events\Dispatcher $dispatcher): self;

    /**
     * Set custom dispatcher jobs
     * 
     * @param Dispatcher $dispatcherJob
     * @return $this
     */
    public function setDispatcherJob(Dispatcher $dispatcherJob): self;
}