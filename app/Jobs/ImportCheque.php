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
use Illuminate\Database\Eloquent\Builder;
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
    protected $cashbox;

    /**
     * ImportCheque constructor.
     *
     * @param \App\Models\Mall $mall
     * @param \stdClass $item
     * @param \stdClass|null $cashbox
     */
    public function __construct(Mall $mall, \stdClass $item, ?\stdClass $cashbox = null)
    {
        $this->mall = $mall;
        $this->item = $item;
        $this->cashbox = $cashbox;
    }

    /**
     * @param string $bin
     * @param string $kkm_code
     *
     * @return \App\Models\Cashbox
     */
    public function loadCashbox(string $bin, string $kkm_code): Cashbox
    {
        /** @var Cashbox $cashbox */
        $cashbox = Cashbox::query()
            ->where('code', $kkm_code)
            ->whereHas('store', function (Builder $builder) use ($bin): Builder {
                return $builder->where('business_identification_number', $bin);
            })->withTrashed()->first();

        if (! $cashbox) {
            /** @var Store $store */
            if (! $store = Store::where('business_identification_number', $bin)->withTrashed()->first()) {
                $store = Store::create([
                    'mall_id' => $this->mall->id,
                    'name' => "БИН: {$bin}",
                    'business_identification_number' => $bin,
                    'type_id' => StoreType::DEFAULT,
                    'deleted_at' => now(),
                ]);
            }

            $cashbox = Cashbox::create([
                'code' => $kkm_code,
                'store_id' => $store->id,
                'mall_id' => $store->mall_id,
                'deleted_at' => now(),
            ]);
        }

        return $cashbox;
    }

    /**
     * @param string $amount
     * @param int $typeId
     *
     * @return float
     */
    protected function getAmount(string $amount, int $typeId): float
    {
        $amount = (float) str_replace(',', '.', $amount);

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
     * @param \stdClass $item
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
