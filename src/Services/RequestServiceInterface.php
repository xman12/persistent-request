<?php

namespace PersistentRequest\Services;

use PersistentRequest\DTO\RequestDTO;

interface RequestServiceInterface
{
    public function execute(RequestDTO $request): void;
}