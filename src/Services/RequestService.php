<?php

namespace PersistentRequest\Services;

use GuzzleHttp\ClientInterface;
use Illuminate\Events\Dispatcher;
use PersistentRequest\DTO\RequestDTO;
use PersistentRequest\Events\SuccessEvent;
use PersistentRequest\Models\RequstModel;
use Throwable;
use Laravel\SerializableClosure\SerializableClosure;

class RequestService implements RequestServiceInterface
{
    protected ClientInterface $client;
    protected Dispatcher $dispatcher;
    protected RequstModel $requstModel;

    public function __construct(ClientInterface $client, Dispatcher $dispatcher, RequstModel $requstModel)
    {
        $this->client = $client;
        $this->dispatcher = $dispatcher;
        $this->requstModel = $requstModel;
    }

    /**
     * @inheritdoc
     * @throws Throwable
     */
    public function execute(RequestDTO $request): void
    {
        // save to db request
        $requestModel = $this->saveRequest($request);
        try {
            if (true === $this->sendRequest($request)) {
                // delete row
                $requestModel->delete();
            }
        }catch (Throwable $exception) {
            
        }
    }

    /**
     * @inheritdoc
     * @throws Throwable
     */
    public function retryExecute(RequstModel $requestModel, RequestDTO $request): void
    {
        $requestModel->increment('count_request');
        try {
            if (true === $this->sendRequest($request)) {
                // delete row
                $requestModel->delete();
            }
        }catch (Throwable $exception) {
            
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
            $response = $this->client->send($request->request);
        } catch (Throwable $exception) {
            throw $exception;
        } finally {
            if (isset($response)) {
                $extendedLogic = $request->extendedLogic;
                if (null !== $extendedLogic) {
                    $extendedLogic($response);
                }
            }
        }
        $this->dispatcher->dispatch(new $request->event($response));

        return true;
    }

    /**
     * Save request data
     *
     * @param RequestDTO $requestDTO
     * @return RequstModel
     * @throws \Laravel\SerializableClosure\Exceptions\PhpVersionNotSupportedException
     */
    protected function saveRequest(RequestDTO $requestDTO): RequstModel
    {
        $requestDTO->extendedLogic = (new SerializableClosure($requestDTO->extendedLogic));
        $requestModel = clone $this->requstModel;
        $requestModel->serialize_data = serialize($requestDTO);
        $requestModel->count_request = 1;
        $requestModel->save();

        return $requestModel;
    }

}
