<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Fetch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ads:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Добавление объявлений в очередь';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $controller = app()->make('App\Http\Controllers\AdController');
        app()->call([$controller, 'fetch'], []); // В пустой массив можно передать аргументы [user_id] => 10 etc'

        $this->info('Обьявления поставлены в очередь');
    }
}
