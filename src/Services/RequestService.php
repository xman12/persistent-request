<?php

namespace PersistentRequest\Services;

use GuzzleHttp\ClientInterface;
use Illuminate\Events\Dispatcher;
use PersistentRequest\DTO\RequestDTO;
use PersistentRequest\Events\SuccessEvent;
use PersistentRequest\Models\RequstModel;
use Throwable;

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

    public function execute(RequestDTO $request)
    {
        // save to db request
        try {
//            $this->saveRequest($request);
            $response = $this->client->send($request->request);
        }catch (Throwable $exception)
        {

        } finally {
            if (null !== $response) {
                $request->extendedLogic($response);
            }
        }

        $this->dispatcher->dispatch(new $request->event($response));
    }

    protected function saveRequest(RequestDTO $requestDTO)
    {
        $requestModel = clone $this->requstModel;
        $requestModel->serialize_data = serialize($requestDTO);
        $requestModel->count_request = 1;
        $requestModel->save();
    }

}
