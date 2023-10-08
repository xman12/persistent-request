<?php

namespace PersistentRequest\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'persistent-request:create-table';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create table persistent_request';
    
    public function handle()
    {
        $this->info('Start created table');
        Schema::create('persistent_request', function (Blueprint $table) {
            $table->increments('id');
            $table->longText('serialize_data');
            $table->integer('count_request');
            $table->timestamps();
        });
        
        $this->info('Command finished');
    }
    
    
}