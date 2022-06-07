<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use AmrShawky\LaravelCurrency\Facade\Currency;
use App\Models\Rate;

class CurrencyCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'currency:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This updates the currency rate';

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
        foreach (Rate::all() as $r) {
            $r->rate = Currency::convert()
                                ->from($r->fromCurr)
                                ->to($r->toCurr)
                                ->throw(function ($response, $e) {
                                    \Log::info($e);
                                })
                                ->get();
            $r->save();
        }

        \Log::info("Currency rate updated!");
    }
}
