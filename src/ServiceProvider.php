<?php

namespace PersistentRequest;

use GuzzleHttp\Client;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\ServiceProvider as AbstractServiceProvider;
use PersistentRequest\Commands\{CreateTableCommand, RetryRequestCommand};
use PersistentRequest\Events\RetryRequestEvent;
use PersistentRequest\Listeners\RetryRequestListener;
use PersistentRequest\Models\RequstModel;
use PersistentRequest\Services\{RequestService, RequestServiceInterface};
use \Illuminate\Contracts\Foundation\Application;

class ServiceProvider extends AbstractServiceProvider
{
    public function register()
    {
        $this->app->singleton(RequestServiceInterface::class, fn(Application $app) => new RequestService(
            $app->make(Client::class),
            $app->make(\Illuminate\Contracts\Bus\Dispatcher::class),
            $app->make('events'),
        ));
    }
}