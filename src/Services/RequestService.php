<?php

namespace PersistentRequest\Services;

use GuzzleHttp\ClientInterface;
use Illuminate\Contracts\Bus\Dispatcher;
use PersistentRequest\DTO\RequestDTO;
use PersistentRequest\Events\RetryRequestEvent;
use PersistentRequest\Jobs\RetryRequestJob;
use PersistentRequest\Models\RequstModel;
use Throwable;
use Laravel\SerializableClosure\SerializableClosure;

class RequestService implements RequestServiceInterface
{
    protected ClientInterface $client;
    protected Dispatcher $dispatcherJob;
    protected \Illuminate\Events\Dispatcher $dispatcher;

    public function __construct(
        ClientInterface $client,
        Dispatcher $dispatcherJob,
        \Illuminate\Events\Dispatcher $dispatcher
    )
    {
        $this->client = $client;
        $this->dispatcherJob = $dispatcherJob;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @inheritdoc
     * @throws Throwable
     */
    public function execute(RequestDTO $request): bool
    {
        try {
            return $this->sendRequest($request);
        } catch (Throwable $exception) {
            return false;
        }
    }

    /**
     * @inheritdoc
     * @throws Throwable
     */
    public function retryExecute(RequestDTO $request): bool
    {
        $request->setCurrentTime(time());
        try {
            return $this->sendRequest($request);
        } catch (Throwable $exception) {
            dump($exception);
            return false;
        }
    }

    /**
     * Send request with execute extended logic and dispatch event
     *
     * @param RequestDTO $request
     * @return bool
     * @throws Throwable
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Laravel\SerializableClosure\Exceptions\PhpVersionNotSupportedException
     */
    protected function sendRequest(RequestDTO $request): bool
    {
        try {
            $response = $this->client->send($request->getRequest());
        } catch (Throwable $exception) {
            $this->dispatchRetryJob($request, $exception);

            return false;
        } finally {
            if (isset($response)) {
                $extendedLogic = $request->getExtendedLogic();
                if (null !== $extendedLogic) {
                    try {
                        $extendedLogic($response);
                    } catch (Throwable $exception) {
                        $this->dispatchRetryJob($request, $exception);

                        return false;
                    }
                }
            }
        }

        $event = $request->getEvent();
        $this->dispatcher->dispatch(new $event($response));

        return true;
    }

    /**
     * @param RequestDTO $request
     * @param Throwable $exception
     * @return void
     */
    private function dispatchRetryJob(RequestDTO $request, Throwable $exception): void
    {
        $request->setAttemps($request->getAttemps() + 1);
        $request->setException($exception);

        $event = new RetryRequestEvent($request);
        $delay = $request->getCurrentTime() + $request->getSleepSecond() - time();
        $job = (new RetryRequestJob($event))->delay($delay);
        $this->dispatcherJob->dispatch($job);
    }
}
