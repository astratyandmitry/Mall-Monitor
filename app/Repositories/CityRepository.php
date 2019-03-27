<?php

namespace App\Repositories;

use App\Models\City;

/**
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2019, ArmenianBros. <i@armenianbros.com>
 */
class CityRepository
{

    /**
     * @return array
     */
    public static function getOptions(): array
    {
        return City::pluck('name', 'id')->toArray();
    }


    /**
     * @return array
     */
    public static function getOptionsGrouped(): array
    {
        /** @var City[] $cities */
        $cities = City::with(['country'])->get();
        $options = [];

        foreach ($cities as $city) {
            $options[$city->country->name][$city->id] = $city->name;
        }

        return $options;
    }

}
