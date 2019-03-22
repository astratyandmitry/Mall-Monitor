<?php

namespace App\Repositories;

use App\Models\Store;

/**
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2019, ArmenianBros. <i@armenianbros.com>
 */
class StoreRepository
{

    /**
     * @return array
     */
    public static function getOptions(): array
    {
        return Store::pluck('name', 'id')->toArray();
    }

}
