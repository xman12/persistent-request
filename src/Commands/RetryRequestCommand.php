<?php

namespace PersistentRequest\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use PersistentRequest\DTO\RequestDTO;
use PersistentRequest\Models\RequstModel;
use PersistentRequest\Services\RequestServiceInterface;

class RetryRequestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'persistent-request:retry';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retry request from db';

    /** @var RequestServiceInterface  */
    protected RequestServiceInterface $requestService;

    /** @var RequstModel  */
    protected RequstModel $requstModel;

    public function __construct(RequestServiceInterface $requestService, RequstModel $requstModel)
    {
        $this->requestService = $requestService;
        $this->requstModel = $requstModel;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Begin retry requests...');
        /** @var RequstModel[]|Collection $requests */
        $requests = $this->requstModel->newQuery()->get();
        $bar = $this->output->createProgressBar($requests->count());
        $bar->start();
        foreach ($requests as $request) {
            /** @var RequestDTO $requestDTO */
            $requestDTO = unserialize($request->serialize_data);
            $this->requestService->retryExecute($request, $requestDTO);
            $bar->advance();
        }
        $this->info(' ');
        $this->info('Finish');
    }
}
