<?php

namespace App\Repositories;

use App\Models\Country;

/**
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2019, ArmenianBros. <i@armenianbros.com>
 */
class CountryRepository
{
    /**
     * @return array
     */
    public static function getOptions(): array
    {
        return Country::pluck('name', 'id')->toArray();
    }
}
