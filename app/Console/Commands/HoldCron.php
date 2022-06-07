<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon;
use App\Models\Hold;

class HoldCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hold:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return int
     */
    public function handle()
    {
        $actualTime = Carbon\Carbon::now();
        foreach (Hold::all() as $lockData) {
            if($actualTime->gt($lockData->lockuntil)){
                $lockData->delete();
            }
        }
        \Log::info("Term deposit lock data updated!");
    }
}
