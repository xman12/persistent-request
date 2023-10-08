# Library for create persistent request.
## Why is it needed?
When to create a fault-tolerant request. 


## Compatibility
- php >=7.4
- guzzlehttp/guzzle >=6.3
- laravel/framework >=7.0
- laravel/serializable-closure "^1.3"

## Installation

> composer require xman12/persistent-request

after library has been installed, publish its configuration file using:

> php artisan vendor:publish --provider="PersistentRequest\Providers\PersistentRequestServiceProvider"

Now need create table, call command:
> php artisan persistent-request:create-table

For auto call down request need configuration [Laravel schedule](https://laravel.com/docs/10.x/scheduling)

Add command:
> $schedule->command(RetryRequestCommand::class)->everyMinute();

### Example for create persistent request
>       $requestGuzzle = new Request('get', 'https://google.com');
>       $requestDTO = new RequestDTO($requestGuzzle,  SuccessEvent::class, function (Response $response) {
>            if (200 !== $response->getStatusCode()) {
>                throw new \Exception('error processed');
>            } else {
>                dispatch(new Event());
>            }
>        });
>        $this->requestService->execute($requestDTO);
> 

