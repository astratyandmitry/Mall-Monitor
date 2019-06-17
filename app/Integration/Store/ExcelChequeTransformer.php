<?php

namespace App\Integration\Store;

use App\Models\Cashbox;
use App\Models\Developer;
use App\Models\ChequeType;
use App\Models\ChequePayment;

/**
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2019, ArmenianBros. <i@armenianbros.com>
 */
class ExcelChequeTransformer
{

    /**
     * @var array
     */
    protected $item;

    /**
     * @var \App\Models\Developer
     */
    protected $developer;


    /**
     * @param array $item
     *
     * @return void
     */
    public function __construct(array $item)
    {
        $this->item = $item;
        $this->developer = Developer::query()->findOrFail(auth('api')->id());
    }


    /**
     * @return array
     */
    public function onlyRequired(): array
    {
        return [
            'code' => (string)@$this->item['code'],
            'number' => (string)@$this->item['number'],
            'amount' => (float)@$this->item['amount'],
            'created_at' => date('Y-m-d H:i:s', strtotime((string)@$this->item['created_at'])),
        ];
    }


    /**
     * @return array
     */
    public function toAttributes(): array
    {
        $cashbox = $this->getCashbox();

        return [
            'kkm_code' => $cashbox->code,
            'code' => (string)@$this->item['code'],
            'number' => (string)@$this->item['number'],
            'amount' => (float)@$this->item['amount'],
            'mall_id' => $this->developer->mall_id,
            'store_id' => $this->developer->store_id,
            'cashbox_id' => $cashbox->id,
            'type_id' => $this->getTypeId(),
            'payment_id' => $this->getPaymentId(),
            'created_at' => date('Y-m-d H:i:s', strtotime((string)@$this->item['created_at'])),
        ];
    }


    /**
     * @return \App\Models\Cashbox
     */
    protected function getCashbox(): Cashbox
    {
        $kkm_code = (isset($this->item['kkm_code']) && (string)$this->item['kkm_code'])
            ? (string)$this->item['kkm_code'] : Cashbox::generateCodeFor($this->developer->store);

        /** @var \App\Models\Cashbox $cashbox */
        if ($cashbox = Cashbox::query()->where('store_id', $this->developer->store_id)->where('code', $kkm_code)->first()) {
            return $cashbox;
        }

        return Cashbox::create([
            'code' => $kkm_code,
            'mall_id' => $this->developer->mall_id,
            'store_id' => $this->developer->store_id,
        ]);
    }


    /**
     * @return int
     */
    protected function getTypeId(): int
    {
        if (isset($this->item['type_id']) && in_array((int)$this->item['type_id'], ChequeType::$options)) {
            return (int)$this->item['type_id'];
        }

        return ChequeType::SELL;
    }


    /**
     * @return int
     */
    protected function getPaymentId(): int
    {
        if (isset($this->item['payment_id']) && in_array((int)$this->item['payment_id'], ChequePayment::$options)) {
            return (int)$this->item['payment_id'];
        }

        return ChequePayment::CASH;
    }

}
