<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Services\ScrapperService;

class UpdateProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */

     protected $goutteClient;

    public function handle()
    {
       $scrapper = new ScrapperService;
       $scrapper->scrapping();
       $scrapper->scrappingFerreteria();
    }


}
