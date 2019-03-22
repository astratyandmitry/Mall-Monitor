<?php

namespace App\Repositories;

use App\Models\Cashbox;

/**
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2019, ArmenianBros. <i@armenianbros.com>
 */
class CashboxRepository
{

    /**
     * @param int|null $store_id
     *
     * @return array
     */
    public static function getOptionsForStore(?int $store_id = null): array
    {
        return Cashbox::where('store_id', $store_id ?? -1)->pluck('code', 'id')->toArray();
    }

}
