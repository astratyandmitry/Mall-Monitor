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
abstract class ChequeTransformer
{

    /**
     * @var \App\Models\Developer
     */
    protected $developer;

    /**
     * @var \App\Models\StoreIntegration
     */
    protected $integration;

    /**
     * @var array
     */
    protected $optionsTypes;

    /**
     * @var array
     */
    protected $optionsPayments;


    /**
     * @return void
     */
    public function __construct()
    {
        $this->developer = Developer::query()->findOrFail(auth('api')->id());
        $this->integration = $this->developer->store->integration;

        $this->optionsTypes = ChequeType::query()->pluck('id', 'system_key')->toArray();
        $this->optionsPayments = ChequePayment::query()->pluck('id', 'system_key')->toArray();
    }


    /**
     * @return array
     */
    public function onlyRequired(): array
    {
        return [
            'code' => (string)$this->getAttribute('code'),
            'number' => (string)$this->getAttribute('number'),
            'amount' => (float)$this->getAttribute('amount'),
            'created_at' => $this->getDateAttribute(),
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
            'code' => (string)($this->getAttribute('code')) ? $this->getAttribute('code') : $this->getAttribute('number'),
            'number' => (string)$this->getAttribute('number'),
            'amount' => (float)$this->getAttribute('amount'),
            'mall_id' => $this->developer->mall_id,
            'store_id' => $this->developer->store_id,
            'cashbox_id' => $cashbox->id,
            'type_id' => $this->getTypeId(),
            'payment_id' => $this->getPaymentId(),
            'created_at' => $this->getDateAttribute(),
        ];
    }


    /**
     * @return \App\Models\Cashbox
     */
    protected function getCashbox(): Cashbox
    {
        $kkm_code = $this->getAttribute('kkm_code');

        if ( ! $kkm_code) {
            $kkm_code = Cashbox::generateCodeFor($this->developer->store);
        }

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
        $type = $this->getAttribute('type_id');

        if ( ! empty($type)) {
            if ($this->integration) {
                $key = array_search($type, $this->integration->types);

                if (isset($this->optionsTypes[$key])) {
                    return $this->optionsTypes[$key];
                }
            } else {
                return $type;
            }
        }

        return ChequeType::SELL;
    }


    abstract protected function getAttribute(string $key);


    abstract protected function getDateAttribute(): string;


    /**
     * @return int
     */
    protected function getPaymentId(): int
    {
        $payment = $this->getAttribute('payment_id');

        if ( ! empty($payment)) {
            if ($this->integration) {
                $key = array_search($payment, $this->integration->payments);

                if (isset($this->optionsPayments[$key])) {
                    return $this->optionsPayments[$key];
                }
            } else {
                return $payment;
            }
        }

        return ChequePayment::CASH;
    }

}
