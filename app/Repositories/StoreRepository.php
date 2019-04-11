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
     * @param int|null $mall_id
     *
     * @return array
     */
    public static function getOptions(?int $mall_id = null): array
    {
        if (is_null($mall_id)) {
            return Store::pluck('name', 'id')->toArray();
        }

        return Store::where('mall_id', $mall_id)->pluck('name', 'id')->toArray();
    }


    /**
     * @param int|null $mall_id
     *
     * @return array
     */
    public static function getLegalOptions(?int $mall_id = null): array
    {
        if (is_null($mall_id)) {
            return Store::pluck('name_legal', 'id')->toArray();
        }

        return Store::where('mall_id', $mall_id)->pluck('name_legal', 'id')->toArray();
    }


    /**
     * @return array
     */
    public static function getOptionsGrouped(): array
    {
        /** @var Store[] $stores */
        $stores = Store::with(['mall'])->get();
        $options = [];

        foreach ($stores as $store) {
            $options[$store->mall->name][$store->id] = $store->name;
        }

        return $options;
    }


    /**
     * @return array
     */
    public static function getLegalOptionsGrouped(): array
    {
        /** @var Store[] $stores */
        $stores = Store::with(['mall'])->get();
        $options = [];

        foreach ($stores as $store) {
            $options[$store->mall->name][$store->id] = $store->name_legal;
        }

        return $options;
    }

}
