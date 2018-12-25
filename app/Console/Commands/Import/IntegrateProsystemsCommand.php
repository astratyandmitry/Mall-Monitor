<?php

namespace App\Console\Commands\Import;

use App\Models\Mall;
use App\Models\Store;
use App\Models\Cheque;
use App\Models\ChequeItem;
use App\Models\ChequeType;
use App\Models\ChequePayment;
use App\Integration\Prosystems;
use Illuminate\Console\Command;
use App\Models\IntegrationSystem;

class IntegrateProsystemsCommand extends Command
{

    /**
     * @var string
     */
    protected $signature = 'mallmonitor:integrate-prosystems';

    /**
     * @var string
     */
    protected $description = 'Integrate with Prosystems';

    /**
     * @var \App\Integration\Prosystems
     */
    protected $integration;

    /**
     * @var \App\Models\Mall
     */
    protected $mall;


    public function __construct()
    {
        $this->mall = Mall::find(Mall::KERUEN_CITY);

        if ($this->mall) {
            $integration = $this->mall->getIntegration(IntegrationSystem::PROSYSTEMS);

            if ($integration) {
                $this->integration = Prosystems::init($integration);
            }
        }

        parent::__construct();
    }


    /**
     * @return void
     */
    public function handle(): void
    {
        if ($this->integration->authorize()) {
            if ($this->integration->provoidData()) {
                foreach ($this->integration->getData() as $item) {
                    // Only selling
                    if ( ! isset($item->Type) || ! in_array($item->Type, ['Sell', 'SellReturn'])) {
                        $this->error("Skip because of type {$item->UniqueId}");

                        continue;
                    }

                    // Only card and cash
                    if (
                        ! isset($item->Payments) || ! isset($item->Payments->Payment) || ! isset($item->Payments->Payment->Type) ||
                        ! in_array($item->Payments->Payment->Type, ['Cash', 'Card'])
                    ) {
                        $this->error("Skip because of paymet {$item->UniqueId}");

                        continue;
                    }

                    $this->info("Adding {$item->UniqueId}");

                    $cheque = $this->createCheque($item);

                    if (is_array($item->Items->Item)) {
                        foreach ($item->Items->Item as $_item) {
                            $this->createChequeItem($cheque, $_item);
                        }
                    } else {
                        $this->createChequeItem($cheque, $item->Items->Item);
                    }
                }

                $this->wsdl->confirmData();
            } else {
                $this->error('There are no available files for import');
            }
        } else {
            $this->error('Unauthorized');
        }
    }


    /**
     * @param \stdClass $item
     *
     * @return \App\Models\Cheque
     */
    protected function createCheque(\stdClass $item): Cheque
    {
        return Cheque::create([
            'mall_id' => $this->mall->id,
            'store_id' => $this->loadStore($item->TaxPayerBIN),
            'kkm_code' => $item->KKMCode,
            'code' => $item->UniqueId,
            'number' => $item->DocumentNumber,
            'amount' => $item->Amount,
            'type_id' => $item->Type == 'Sell' ? ChequeType::SELL : ChequeType::SELL_RETURN,
            'payment_id' => $item->Payments->Payment->Type == 'Cash' ? ChequePayment::CASH : ChequePayment::CARD,
            'created_at' => $item->DateTime,
            'data' => [
                'Cashier' => $item->Cashier,
                'WorkSessionNumber' => $item->WorkSessionNumber,
            ],
        ]);
    }


    /**
     * @param \App\Models\Cheque $cheque
     * @param \stdClass          $item
     *
     * @return mixed
     */
    protected function createChequeItem(Cheque $cheque, \stdClass $item)
    {
        return ChequeItem::create([
            'cheque_id' => $cheque->id,
            'code' => $item->Code,
            'name' => $item->Name,
            'price' => (float)$item->Price,
            'quantity' => (int)$item->Quantity,
            'sum' => (float)$item->Sum,
        ]);
    }


    /**
     * @param string $bin
     *
     * @return int
     */
    protected function loadStore(string $bin): int
    {
        if ( ! $store = Store::where('mall_id', $this->mall->id)->where('business_identification_number', $bin)->first()) {
            $store = Store::create([
                'mall_id' => $this->mall->id,
                'name' => 'Без названия',
                'business_identification_number' => $bin,
            ]);
        }

        return $store->id;
    }

}
