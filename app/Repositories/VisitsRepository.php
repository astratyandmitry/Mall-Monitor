<?php

namespace App\Repositories;

use App\Classes\GraphDate;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2019, ArmenianBros. <i@armenianbros.com>
 */
class VisitsRepository
{

    /**
     * @param int|null $mall_id
     * @param int      $limit
     *
     * @return \Illuminate\Support\Collection
     */
    public static function getAggregatedForMall(?int $mall_id = null, int $limit = 30): Collection
    {
        $dateColumn = GraphDate::instance()->getDateColumn();

        /** @var \Illuminate\Database\Query\Builder $items */
        $items = DB::table('visits')
            ->select(DB::raw("SUM(count) AS count, {$dateColumn} as date"))
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->limit($limit);

        if ( ! is_null($mall_id)) {
            $items = $items->where('mall_id', $mall_id);
        }

        return $items->get();
    }


    /**
     * @param int $store_id
     * @param int $limit
     *
     * @return \Illuminate\Support\Collection
     */
    public static function getAggregatedForStore(int $store_id, int $limit = 30): Collection
    {
        $dateColumn = GraphDate::instance()->getDateColumn();

        return DB::table('visits')
            ->select(DB::raw("SUM(count) AS count, {$dateColumn} as date"))
            ->where('store_id', $store_id)
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->limit($limit)
            ->get();
    }


    /**
     * @return array
     */
    public static function getAggregatedMonthForMall(): array
    {
        $startedDate = date('Y') . '-' . date('m') . '-01 00:00:00';

        return DB::table('visits')
            ->select(DB::raw('COUNT(*) AS count, SUM(count) as count, mall_id'))
            ->where('created_at', '>=', $startedDate)
            ->groupBy('mall_id')
            ->pluck('count', 'mall_id')->toArray();
    }


    /**
     * @param int|null $mall_id
     *
     * @return array
     */
    public static function getAggregatedMonthForStore(?int $mall_id = null): array
    {
        $startedDate = date('Y') . '-' . date('m') . '-01 00:00:00';

        /** @var \Illuminate\Database\Query\Builder $items */
        $items = DB::table('visits')
            ->select(DB::raw('COUNT(*) AS count, SUM(count) as count, store_id'))
            ->where('created_at', '>=', $startedDate)
            ->where('store_id', '>', 0)
            ->groupBy('store_id');

        if ( ! is_null($mall_id)) {
            $items = $items->where('mall_id', $mall_id);
        }

        return $items->pluck('count', 'store_id')->toArray();
    }

}
