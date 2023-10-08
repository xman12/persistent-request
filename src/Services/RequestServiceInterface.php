<?php

namespace PersistentRequest\Services;

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
     * @param bool $showThrowable
     * @return void
     */
    public function execute(RequestDTO $request, bool $showThrowable = false): void;

    /**
     * Retry request from database 
     * 
     * @param RequstModel $requestModel
     * @param RequestDTO $request
     * @return void
     */
    public function retryExecute(RequstModel $requestModel, RequestDTO $request): void;
}