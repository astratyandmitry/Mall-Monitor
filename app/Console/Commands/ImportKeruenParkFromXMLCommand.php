<?php

namespace App\Console\Commands;

use App\Models\Cashbox;
use App\Models\Cheque;
use App\Models\ChequePayment;
use App\Models\ChequeType;
use Illuminate\Console\Command;

class ImportKeruenParkFromXMLCommand extends Command
{

    const MALL_ID = 1;
    const STORE_ID = 2;
    const KKM_CODE = 'keruenpark01';

    /**
     * @var string
     */
    protected $signature = 'mallmonitor:import-keruen-park-xml';

    /**
     * @var string
     */
    protected $description = 'Import Keruen Part from XML';


    /**
     * @return void
     */
    public function handle(): void
    {
        $filepath = storage_path('/import/keruen-park.xml');

        if ( ! \File::exists($filepath)) {
            $this->error('The «keruen-park.xml» file not found');

            return;
        }

        $data = simplexml_load_string(file_get_contents($filepath));

        foreach ($data as $item) {
            $this->info("Adding {$item->DOC_ID}");

            Cheque::create([
                'kkm_code' => self::KKM_CODE,
                'code' => $item->DOC_ID,
                'number' => $item->DOC_NUMBER,
                'amount' => (int)$item->DOC_AMNT,
                'data' => [],
                'mall_id' => self::MALL_ID,
                'store_id' => self::STORE_ID,
                'cashbox_id' => $this->getCahsbox()->id,
                'type_id' => ChequeType::SELL,
                'payment_id' => ChequePayment::CASH,
                'created_at' => "{$item->DOC_DATE} 12:00:00",
            ]);
        }

        \File::delete($filepath);
    }



    protected function getCahsbox(): Cashbox
    {
        if ($cashbox = Cashbox::where('store_id', self::STORE_ID)->where('code', self::KKM_CODE)->first()) {
            return $cashbox;
        }

        return Cashbox::create([
            'mall_id' => self::MALL_ID,
            'store_id' => self::STORE_ID,
            'code' => self::KKM_CODE,
        ]);
    }

}
