<?php

namespace App\Jobs;

use App\Models\Cheque;
use App\Models\ChequeItem;
use App\Models\ChequeType;
use App\Models\ChequePayment;

class ImportChequeProsystem extends ImportCheque
{
    /**
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
     * @return void
     */
    public function handle(): void
    {
        if ($cheque = $this->createCheque($this->item)) {
            if (! property_exists($this->item, 'Items') || ! count($this->item->Items)) {
                return;
            }

            if (is_array($this->item->Items->Item)) {
                foreach ($this->item->Items->Item as $_item) {
                    $this->createChequeItem($cheque, $_item);
                }
            } else {
                $this->createChequeItem($cheque, $this->item->Items->Item);
            }
        }
    }

    /**
     * @param \stdClass $item
     *
     * @return \App\Models\Cheque|null
     */
    protected function createCheque(\stdClass $item): ?Cheque
    {
        $cashbox = $this->loadCashbox($item->TaxPayerBIN, $item->KKMCode);
        $typeId = $this->getType($item->Type);

        $exists = Cheque::query()->where([
            'mall_id' => $cashbox->mall_id,
            'store_id' => $cashbox->store_id,
            'cashbox_id' => $cashbox->id,
            'kkm_code' => $cashbox->code,
            'code' => $item->Number,
        ])->exists();

        if ($exists) {
            return null;
        }

        return Cheque::create([
            'mall_id' => $cashbox->mall_id,
            'store_id' => $cashbox->store_id,
            'cashbox_id' => $cashbox->id,
            'kkm_code' => $cashbox->code,
            'code' => $item->UniqueId,
            'number' => $item->DocumentNumber,
            'shift_number' => $item->WorkSessionNumber,
            'amount' => $this->getAmount($item->Amount, $typeId),
            'type_id' => $typeId,
            'payment_id' => $this->getPaymentId($item),
            'created_at' => $item->DateTime,
            'data' => [
                'Cashier' => $item->Cashier,
            ],
        ]);
    }

    /**
     * @param \stdClass $item
     *
     * @return int
     */
    protected function getPaymentId(\stdClass $item): int
    {
        if (! property_exists($item, 'Payments') || ! property_exists($item->Payments, 'Payment')) {
            return ChequePayment::CASH;
        }

        return $this->getPayment((is_array($item->Payments->Payment)) ? $item->Payments->Payment[0]->Type : $item->Payments->Payment->Type);
    }

    /**
     * @param \App\Models\Cheque $cheque
     * @param \stdClass $item
     *
     * @return \App\Models\ChequeItem|null
     */
    protected function createChequeItem(Cheque $cheque, \stdClass $item): ?ChequeItem
    {
        return $cheque->items()->create([
            'code' => $item->Code,
            'name' => $item->Name,
            'price' => (float) $item->Price,
            'quantity' => (int) $item->Quantity,
            'sum' => (float) $item->Sum,
        ]);
    }
}
