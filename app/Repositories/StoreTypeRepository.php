<?php

namespace App\Repositories;

use App\Models\StoreType;

/**
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2019, ArmenianBros. <i@armenianbros.com>
 */
class StoreTypeRepository
{
    /**
     * @return array
     */
    public static function getOptions(): array
    {
        return StoreType::pluck('name', 'id')->toArray();
    }
}
