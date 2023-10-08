<?php

namespace PersistentRequest\Services;

use PersistentRequest\DTO\RequestDTO;
use PersistentRequest\Models\RequstModel;

interface RequestServiceInterface
{
    /**
     * Execute request 
     * 
     * @param RequestDTO $request
     * @return void
     */
    public function execute(RequestDTO $request): void;

    /**
     * Retry request from database 
     * 
     * @param RequstModel $requestModel
     * @param RequestDTO $request
     * @return void
     */
    public function retryExecute(RequstModel $requestModel, RequestDTO $request): void;
}