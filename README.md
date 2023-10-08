# Library for create persistent request.
## Why is it needed?
When to create a fault-tolerant request. 


## Compatibility
- php >=7.4
- guzzlehttp/guzzle >=6.3
- laravel/framework >=7.0
- laravel/serializable-closure "^1.*"

## Installation

> composer require xman12/persistent-request

after library has been installed, publish its configuration file using:

> php artisan vendor:publish --provider="PersistentRequest\ServiceProvider"

or add the following providers in config/app.php:
>'providers' => [PersistentRequest\ServiceProvider::class]

Now need create table, call command:
> php artisan persistent-request:create-table

For auto call down request need configuration [Laravel schedule](https://laravel.com/docs/10.x/scheduling)

Add command:
> $schedule->command(RetryRequestCommand::class)->everyMinute();

### Example for create persistent request
>        $requestService = app(\PersistentRequest\Services\RequestServiceInterface::class);
>        $requestGuzzle = new \GuzzleHttp\Psr7\Request('get', 'https://xman12.video-chat.site/json-test');
>        $requestDTO = new \PersistentRequest\DTO\RequestDTO($requestGuzzle, \PersistentRequest\Events\SuccessEvent::class, function (\GuzzleHttp\Psr7\Response $response) {
>            if (200 !== $response->getStatusCode()) {
>                throw new \Exception('error processed');
>            }
>        });
>
>        $requestService->execute($requestDTO); 

