<?php

namespace App\Repositories;

use App\Models\Store;
use Illuminate\Support\Collection;

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
        $query = Store::query();

        if (! is_null($mall_id)) {
            $query = $query->where('mall_id', $mall_id);
        }

        /** @var \App\Models\Store[] $stores */
        $stores = $query->orderBy('business_identification_number')->get();
        $options = [];

        foreach ($stores as $store) {
            $options[$store->id] = "{$store->business_identification_number}: {$store->name}";
        }

        return $options;
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

    /**
     * @param int|null $mall_id
     *
     * @return \Illuminate\Support\Collection
     */
    public static function getListForMall(?int $mall_id): Collection
    {
        /** @var \Illuminate\Database\Query\Builder $items */
        $items = Store::query()->orderBy('name');

        if (! is_null($mall_id)) {
            $items = $items->where('mall_id', $mall_id);
        }

        return $items->get();
    }
}
