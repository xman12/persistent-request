<?php

namespace PersistentRequest\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use PersistentRequest\Events\RetryRequestEvent;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use PersistentRequest\Services\RequestServiceInterface;

/**
 * Job for retry send request
 */
class RetryRequestJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected RetryRequestEvent $retryRequestEvent;

    public function __construct(RetryRequestEvent $retryRequestEvent)
    {
        $this->retryRequestEvent = $retryRequestEvent;
    }

    public function handle(RequestServiceInterface $requestService)
    {
        $requestDTO = $this->retryRequestEvent->getRequestDTO();
        if ((null === $requestDTO->getMaxAttemps())
            || ($requestDTO->getAttemps() < $requestDTO->getMaxAttemps())
        ) {
            try {
                $requestService->retryExecute($requestDTO);
                return true;
            } catch (\Throwable $exception) {
                return true;
            }
        } else {
            $this->delete();
        }
    }

}
