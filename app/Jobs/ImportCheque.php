<?php

namespace App\Jobs;

use App\Models\Mall;
use App\Models\Store;
use App\Models\Cheque;
use App\Models\Cashbox;
use App\Models\ChequeType;
use App\Models\ChequeItem;
use App\Models\ChequePayment;
use App\Models\StoreType;
use Illuminate\Foundation\Bus\Dispatchable;

/**
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2019, ArmenianBros. <i@armenianbros.com>
 */
abstract class ImportCheque
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

    /**
     * @var array
     */
    protected $cashbox_codes = [];

    /**
     * @var array
     */
    protected $payments = [];

    /**
     * @var array
     */
    protected $types = [];

    /**
     * @var string|null
     */
    protected $bin;


    /**
     * @param \App\Models\Mall $mall
     * @param \stdClass        $item
     */
    public function __construct(Mall $mall, \stdClass $item, ?string $bin = null)
    {
        $this->mall = $mall;
        $this->item = $item;
        $this->bin = $bin;

        $this->loadCashboxCodes();
    }


    /**
     * @return void
     */
    protected function loadCashboxCodes(): void
    {
        $cashboxes = Cashbox::withTrashed()->get();

        foreach ($cashboxes as $cashbox) {
            $this->cashbox_codes[$cashbox->mall_id][$cashbox->store_id][$cashbox->code] = $cashbox->id;
        }
    }


    /**
     * @param string $bin
     *
     * @return int
     */
    protected function loadStore(?string $bin = null): int
    {
        $bin = $bin ?? $this->bin;

        if ( ! $store = Store::where('mall_id', $this->mall->id)->where('business_identification_number', $bin)->withTrashed()->first()) {
            $store = Store::create([
                'mall_id' => $this->mall->id,
                'name' => "БИН: {$bin}",
                'business_identification_number' => $bin,
                'type_id' => StoreType::DEFAULT,
            ]);
        }

        return $store->id;
    }


    /**
     * @param int    $storeId
     * @param string $code
     *
     * @return int
     */
    protected function getCashboxCodeId(int $storeId, string $code): int
    {
        if ( ! isset($this->cashbox_codes[$this->mall->id][$storeId][$code])) {
            $this->cashbox_codes[$this->mall->id][$storeId][$code] = Cashbox::create([
                'mall_id' => $this->mall->id,
                'store_id' => $storeId,
                'code' => $code,
            ])->id;
        }

        return $this->cashbox_codes[$this->mall->id][$storeId][$code];
    }


    /**
     * @param string $amount
     * @param int    $typeId
     *
     * @return float
     */
    protected function getAmount(string $amount, int $typeId): float
    {
        $amount = (float)str_replace(',', '.', $amount);

        return (in_array($typeId, [ChequeType::BUY_RETURN, ChequeType::SELL_RETURN])) ? $amount * -1 : $amount;
    }


    /**
     * @param string $key
     * @param string $default
     *
     * @return int
     */
    protected function getPayment(string $key, string $default = ChequePayment::CASH): int
    {
        return (isset($this->payments[$key])) ? $this->payments[$key] : $default;
    }


    /**
     * @param string $key
     * @param string $default
     *
     * @return int
     */
    protected function getType(string $key, string $default = ChequeType::SELL): int
    {
        return (isset($this->types[$key])) ? $this->types[$key] : $default;
    }


    /**
     * @param \App\Models\Cheque $cheque
     * @param \stdClass          $item
     *
     * @return mixed
     */
    abstract protected function createChequeItem(Cheque $cheque, \stdClass $item): ?ChequeItem;


    /**
     * @param \stdClass $item
     *
     * @return \App\Models\Cheque|null
     */
    abstract protected function createCheque(\stdClass $item): ?Cheque;

}
