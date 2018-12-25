<?php

namespace App\Jobs;

use App\Models\Mall;
use App\Models\Store;
use App\Models\Cheque;
use App\Models\ChequeItem;
use App\Models\ChequeType;
use App\Models\ChequePayment;
use Illuminate\Foundation\Bus\Dispatchable;

class ImportChequeProsystem
{

    use Dispatchable;

    /**
     * @var \stdClass
     */
    protected $item;

    /**
     * @var \App\Models\Mall
     */
    protected $mall;

    /**got
     * @var array
     */
    protected $payments = [
        'Cash' => ChequePayment::CASH,
        'Card' => ChequePayment::CARD,
        'Credit' => ChequePayment::CREDIT,
        'Tare' => ChequePayment::TARE,
    ];

    /**
     * @var array
     */
    protected $types = [
        'Sell' => ChequeType::SELL,
        'SellReturn' => ChequeType::SELL_RETURN,
        'Buy' => ChequeType::BUY,
        'BuyReturn' => ChequeType::BUY_RETURN,
        'Deposit' => ChequeType::DEPOSIT,
        'Withdrawal' => ChequeType::WITHDRAWAL,
    ];


    /**
     * @param \App\Models\Mall $mall
     * @param \stdClass        $item
     */
    public function __construct(Mall $mall, \stdClass $item)
    {
        $this->mall = $mall;
        $this->item = $item;
    }


    /**
     * @return void
     */
    public function handle(): void
    {
        $cheque = $this->createCheque($this->item);

        if (is_array($this->item->Items->Item)) {
            foreach ($this->item->Items->Item as $_item) {
                $this->createChequeItem($cheque, $_item);
            }
        } else {
            $this->createChequeItem($cheque, $this->item->Items->Item);
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
            'type_id' => $this->types[$item->Type],
            'payment_id' => $this->payments[$item->Payments->Payment->Type],
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
