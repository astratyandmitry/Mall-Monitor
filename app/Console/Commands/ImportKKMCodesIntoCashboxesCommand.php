<?php

namespace App\Console\Commands;

use App\Models\Cashbox;
use Illuminate\Console\Command;

class ImportKKMCodesIntoCashboxesCommand extends Command
{

    /**
     * @var string
     */
    protected $signature = 'keruenmonitor:import-kkm-codes-cashbox';

    /**
     * @var string
     */
    protected $description = 'Import KKM codes into Cashboxes';


    /**
     * @return void
     */
    public function handle(): void
    {
        $cashboxeCodes = \DB::table('cashboxes')->pluck('code', 'id')->toArray();

        $cheques = \DB::table('cheques')
            ->whereNotIn('kkm_code', $cashboxeCodes)
            ->select(\DB::raw('distinct(kkm_code), mall_id, store_id'))
            ->get();

        if (count($cheques)) {
            foreach ($cheques as $cheque) {
                $this->info("Working with {$cheque->kkm_code}");

                Cashbox::create([
                    'store_id' => $cheque->store_id,
                    'mall_id' => $cheque->mall_id,
                    'code' => $cheque->kkm_code,
                ]);
            }
        } else {
            $this->error('No available codes.');
        }
    }

}
