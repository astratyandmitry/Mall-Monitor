<?php

namespace App\Jobs;

use App\Models\Cheque;
use App\Models\ChequeItem;
use App\Models\ChequeType;
use App\Models\ChequePayment;

class ImportChequeProsklad extends ImportCheque
{
    /**
     * @return void
     */
    public function handle(): void
    {
        if ($cheque = $this->createCheque($this->item)) {
            $this->createChequeItem($cheque, $this->item);
        }
    }

    /**
     * @param \stdClass $item
     *
     * @return \App\Models\Cheque|null
     */
    protected function createCheque(\stdClass $item): ?Cheque
    {
        $cashbox = $this->loadCashbox($this->item->bin, $this->item->factory_number);
        $typeId = ChequeType::SELL;

        $exists = Cheque::query()->where([
            'mall_id' => $cashbox->mall_id,
            'store_id' => $cashbox->store_id,
            'cashbox_id' => $cashbox->id,
            'kkm_code' => $cashbox->code,
            'code' => $item->receipt_number,
        ])->exists();

        if ($exists) {
            return null;
        }

        return Cheque::query()->create([
            'mall_id' => $cashbox->mall_id,
            'store_id' => $cashbox->store_id,
            'cashbox_id' => $cashbox->id,
            'kkm_code' => $cashbox->code,
            'code' => $item->id,
            'number' => $item->receipt_number,
            'shift_number' => $item->cashbox_id,
            'amount' => $this->getAmount($item->sum, $typeId),
            'type_id' => $typeId,
            'payment_id' => ChequePayment::CARD,
            'created_at' => $item->date,
            'data' => [],
        ]);
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
            'code' => $cheque->number,
            'name' => 'Товар',
            'price' => (float) $item->sum,
            'quantity' => 1,
            'sum' => (float) $item->sum,
        ]);
    }
}
