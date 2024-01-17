<?php

namespace App\Jobs;

use App\Models\Cheque;
use App\Models\ChequeItem;
use App\Models\ChequeType;
use App\Models\ChequePayment;

class ImportChequeTrinity extends ImportCheque
{
    /**
     * @var array
     */
    protected $payments = [
        'Наличные' => ChequePayment::CASH,
        'Банковская карта' => ChequePayment::CARD,
        'Оплата в кредит' => ChequePayment::CREDIT,
        'Оплата тарой' => ChequePayment::TARE,
        0 => CheqxuePayment::CASH,
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
            if (! property_exists($this->item, 'positions') || ! count($this->item->positions)) {
                return;
            }

            foreach ($this->item->positions as $item) {
                $this->createChequeItem($cheque, $item);
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
        $cashbox = $this->loadCashbox($this->cashbox->xin, $this->cashbox->cashboxUniqueNumber);
        $typeId = $this->getType($item->operationType);

        $exists = Cheque::query()->where([
            'mall_id' => $cashbox->mall_id,
            'store_id' => $cashbox->store_id,
            'cashbox_id' => $cashbox->id,
            'kkm_code' => $cashbox->code,
            'code' => $item->number,
        ])->exists();

        if ($exists) {
            return null;
        }

        return Cheque::query()->create([
            'mall_id' => $cashbox->mall_id,
            'store_id' => $cashbox->store_id,
            'cashbox_id' => $cashbox->id,
            'kkm_code' => $cashbox->code,
            'code' => $item->number,
            'number' => $item->orderNumber,
            'shift_number' => $item->shiftNumber,
            'amount' => $this->getAmount($item->total, $typeId),
            'type_id' => $typeId,
            'payment_id' => $this->getPaymentId($item),
            'created_at' => \DateTime::createFromFormat('d.m.Y H:i:s', $item->registratedOn)->format('Y-m-d H:i:s'),
            'data' => [],
        ]);
    }

    /**
     * @param \stdClass $item
     *
     * @return int
     */
    protected function getPaymentId(\stdClass $item): int
    {
        if (! property_exists($item, 'payments') || ! count($item->payments) || ! property_exists($item->payments[0], 'paymentTypeName')) {
            return ChequePayment::CASH;
        }

        return $this->getPayment($item->payments[0]->paymentTypeName);
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
            'code' => $item->positionCode ?? null,
            'name' => $item->positionName ?? 'Товар',
            'price' => (float) $item->price,
            'quantity' => (int) $item->count,
            'sum' => (float) $item->sum,
        ]);
    }
}
