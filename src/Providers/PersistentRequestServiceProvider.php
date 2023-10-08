<?php

namespace PersistentRequest\Providers;

use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;
use PersistentRequest\Commands\{CreateTableCommand, RetryRequestCommand};
use PersistentRequest\Models\RequstModel;
use PersistentRequest\Services\{RequestService, RequestServiceInterface};
use \Illuminate\Contracts\Foundation\Application;

class PersistentRequestServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(RequestServiceInterface::class, fn(Application $app) => new RequestService(
            $app->make(Client::class),
            $app->make('events'),
            $app->make(RequstModel::class)
        ));

        // register commands
        $this->commands([
            RetryRequestCommand::class,
            CreateTableCommand::class,
        ]);
    }
}