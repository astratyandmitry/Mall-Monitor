<?php

namespace App\Jobs;

use App\Models\Cheque;
use App\Models\ChequeItem;
use App\Models\ChequeType;
use App\Models\ChequePayment;

class ImportChequeWebKassa extends ImportCheque
{

    /**
     * @var array
     */
    protected $payments = [
        'Наличные' => ChequePayment::CASH,
        'Банковская карта' => ChequePayment::CARD,
        'Оплата в кредит' => ChequePayment::CREDIT,
        'Оплата тарой' => ChequePayment::TARE,
        0 => ChequePayment::CASH,
        1 => ChequePayment::CARD,
        2 => ChequePayment::CREDIT,
        3 => ChequePayment::TARE,
    ];

    /**
     * @var array
     */
    protected $types = [
        0 => ChequeType::BUY,
        1 => ChequeType::BUY_RETURN,
        2 => ChequeType::SELL,
        3 => ChequeType::SELL_RETURN,
    ];


    /**
     * @return void
     */
    public function handle(): void
    {
        if ($cheque = $this->createCheque($this->item)) {
            if ( ! property_exists($this->item, 'Positions') || ! count($this->item->Positions)) {
                return;
            }

            foreach ($this->item->Positions as $item) {
                $this->createChequeItem($cheque, $item);
            }
        }
    }


    /**
     * @param \stdClass $item
     *
     * @return \App\Models\Cheque
     */
    protected function createCheque(\stdClass $item): Cheque
    {
        $storeId = $this->loadStore();
        $typeId = $this->getType($item->OperationType);

        return Cheque::create([
            'mall_id' => $this->mall->id,
            'store_id' => $storeId,
            'cashbox_id' => $this->getCashboxCodeId($storeId, $item->CashboxUniqueNumber),
            'kkm_code' => $item->CashboxUniqueNumber,
            'code' => $item->Number,
            'number' => $item->OrderNumber,
            'amount' => $this->getAmount($item->Total, $typeId),
            'type_id' => $typeId,
            'payment_id' => $this->getPaymentId($item),
            'created_at' => date('Y-m-d H:i:s', strtotime($item->RegistratedOn)),
            'data' => [
                'CashboxRegistrationNumber' => $item->CashboxRegistrationNumber,
                'CashboxIdentityNumber' => $item->CashboxIdentityNumber,
                'ShiftNumber' => $item->ShiftNumber,
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
        if ( ! property_exists($item, 'Payments') || ! count($item->Payments) || ! property_exists($item->Payments[0], 'PaymentTypeName')) {
            return ChequePayment::CASH;
        }

        return $this->getPayment($item->Payments[0]->PaymentTypeName);
    }


    /**
     * @param \App\Models\Cheque $cheque
     * @param \stdClass          $item
     *
     * @return \App\Models\ChequeItem|null
     */
    protected function createChequeItem(Cheque $cheque, \stdClass $item): ?ChequeItem
    {
        return $cheque->items()->create([
            'code' => $item->PositionCode,
            'name' => $item->PositionName,
            'price' => (float)$item->Price,
            'quantity' => (int)$item->Count,
            'sum' => (float)$item->Sum,
        ]);
    }

}