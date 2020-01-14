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
        $cashboxes = DB::table('cashboxes')->where('code', 'like', "SWK%")->pluck('id', 'code')->toArray();

        foreach ($cashboxes as $cashboxCode => $cashboxId) {
            $this->info("Working with: {$cashboxCode}");

            $i = 1;
            while (DB::select("select count(id) as count from cheques where cashbox_id = {$cashboxId}")[0]->count) {
                $this->info("- iteration: {$i}");
                $i++;

                DB::delete("delete from cheques where cashbox_id = {$cashboxId} limit 10000");
            }
        }
    }

}
