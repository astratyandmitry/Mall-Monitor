<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ChequesWebkassaDeleteCommand extends Command
{

    /**
     * @var string
     */
    protected $signature = 'mallmonitor:cheques-webkassa-delete';

    /**
     * @var string
     */
    protected $description = 'Command description';


    /**
     * @return mixed
     */
    public function handle(): void
    {
        $i = 1;
        while (DB::select("select count(id) as count from cheques where kkm_code like 'SWK%'")[0]->count) {
            $this->info("step: {$i}");
            $i++;

            DB::delete("delete from cheques where kkm_code like 'SWK%'");
        }
    }

}
