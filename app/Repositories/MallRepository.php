<?php

namespace App\Repositories;

use App\Models\Mall;

/**
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2019, ArmenianBros. <i@armenianbros.com>
 */
class MallRepository
{

    /**
     * @return array
     */
    public static function getOptions(): array
    {
        return Mall::pluck('name', 'id')->toArray();
    }

}
