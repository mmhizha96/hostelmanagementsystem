<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;


class proccessapplications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:proccessapplications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check if there are any pending applications and take action';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        echo app('App\Http\Controllers\roomApplicationController')->proccessApplications(null);
    }
}
