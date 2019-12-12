<?php

namespace App\Repositories;

use App\Classes\Date\ReportDate;
use App\Models\Cheque;
use App\Models\Visit;
use App\Classes\Graph\GraphDate;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

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


    /**
     * @return \Illuminate\Support\Collection
     */
    public static function getCompareForMall(): Collection
    {
        $dateColumn = GraphDate::instance()->getDateColumn();
        $startedDate = GraphDate::instance()->getStartedDate();

        return Visit::query()
            ->select(DB::raw("SUM(count) AS count, mall_id, {$dateColumn} as date"))
            ->where('created_at', '>=', $startedDate)
            ->groupBy('date', 'mall_id')
            ->orderBy('date', 'asc')
            ->where(function (Builder $builder): Builder {
                if (auth()->user()->mall_id) {
                    $builder->where('mall_id', auth()->user()->mall_id);
                } else {
                    $builder->when(request('mall_id'), function (Builder $builder): Builder {
                        return $builder->where('mall_id', request('mall_id'));
                    });
                }

                $builder->when(request('type_id'), function (Builder $builder): Builder {
                    return $builder->whereHas('store', function (Builder $builder): Builder {
                        return $builder->where('type_id', request('type_id'));
                    });
                });

                return $builder;
            })->get()->groupBy('date');
    }


    /**
     * @return \Illuminate\Support\Collection
     */
    public static function getCompareForStore(): Collection
    {
        $dateColumn = GraphDate::instance()->getDateColumn();
        $startedDate = GraphDate::instance()->getStartedDate();

        return Visit::query()
            ->select(DB::raw("SUM(count) AS count, store_id, {$dateColumn} as date"))
            ->where('created_at', '>=', $startedDate)
            ->groupBy('date', 'store_id')
            ->orderBy('date', 'asc')
            ->where(function (Builder $builder): Builder {
                if (auth()->user()->mall_id) {
                    $builder->where('mall_id', auth()->user()->mall_id);
                } else {
                    $builder->when(request('mall_id'), function (Builder $builder): Builder {
                        return $builder->where('mall_id', request('mall_id'));
                    });
                }

                $builder->when(request('type_id'), function (Builder $builder): Builder {
                    return $builder->whereHas('store', function (Builder $builder): Builder {
                        return $builder->where('type_id', request('type_id'));
                    });
                });

                return $builder;
            })->get()->groupBy('date');
    }


    /**
     * @return \Illuminate\Support\Collection
     */
    public static function getReportForMall(): Collection
    {
        list($dateFrom, $dateTo, $dateGroup) = ReportDate::instance()->getData();

        return Visit::reportMall($dateFrom, $dateTo)
            ->select(DB::raw("SUM(count) AS count, mall_id, created_{$dateGroup} as date"))
            ->groupBy('date')
            ->groupBy('mall_id')
            ->get();
    }


    /**
     * @return \Illuminate\Support\Collection
     */
    public static function getReportForStore(): Collection
    {
        list($dateFrom, $dateTo, $dateGroup) = ReportDate::instance()->getData();

        return Visit::reportMall($dateFrom, $dateTo)
            ->select(DB::raw("SUM(count) AS count, mall_id, store_id, created_{$dateGroup} as date"))
            ->groupBy('date')
            ->groupBy('store_id')
            ->get();
    }


    /**
     * @param string $dateFrom
     * @param string $dateTo
     *
     * @return \Illuminate\Support\Collection|null
     */
    public static function getPlacementForMall(string $dateFrom, string $dateTo): ?Collection
    {
        return Visit::reportMall($dateFrom, $dateTo)
            ->select(DB::raw('SUM(count) AS count, mall_id'))
            ->groupBy('mall_id')
            ->get();
    }


    /**
     * @param string $dateFrom
     * @param string $dateTo
     *
     * @return \Illuminate\Support\Collection|null
     */
    public static function getPlacementForStore(string $dateFrom, string $dateTo): ?Collection
    {
        return Visit::reportMall($dateFrom, $dateTo)
            ->select(DB::raw('SUM(count) AS count, mall_id, store_id'))
            ->groupBy('store_id')
            ->get();
    }

}
