<?php

namespace App\Console\Commands\Import;

use App\Models\Cheque;
use App\Models\ChequeItem;
use App\Models\ChequeType;
use App\Models\ChequePayment;
use App\WSDL\ProsystemsWSDL;
use Illuminate\Console\Command;

class IntegrateProsystemsMultiCommand extends Command
{

    /**
     * @var string
     */
    protected $signature = 'mallmonitor:integrate-prosystems-multi';

    /**
     * @var string
     */
    protected $description = 'Integrate with Prosystems Multi Queries';

    /**
     * @var \App\WSDL\ProsystemsWSDL
     */
    protected $wsdl;


    public function __construct()
    {
        $this->wsdl = ProsystemsWSDL::init();

        parent::__construct();
    }


    /**
     * @return void
     */
    public function handle(): void
    {
        do {
            $status = $this->work();
        } while ($status == true);
    }


    /**
     * @return bool
     */
    protected function work(): bool
    {
        if ($this->wsdl->authorize()) {
            if ($this->wsdl->provoidData()) {
                foreach ($this->wsdl->getData() as $item) {
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

                return true;
            } else {
                return false;
            }
        } else {
            return false;
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
            'mall_id' => 1,
            'store_id' => 1,
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

}
