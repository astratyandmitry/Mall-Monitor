<?php

namespace App\Repositories;

use App\Models\ChequeType;

/**
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2019, ArmenianBros. <i@armenianbros.com>
 */
class ChequeTypeRepository
{

    /**
     * @return array
     */
    public static function getOptions(): array
    {
        return ChequeType::query()->pluck('name', 'system_key')->toArray();
    }

}
