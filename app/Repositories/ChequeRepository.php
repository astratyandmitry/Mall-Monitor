<?php

namespace App\Repositories;

use App\Classes\Date\PlacementDate;
use App\Classes\Date\ReportDate;
use App\Models\Cheque;
use App\Classes\Graph\GraphDate;
use App\Models\ChequeType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2019, ArmenianBros. <i@armenianbros.com>
 */
class ChequeRepository
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

        /** @var \Illuminate\Database\Query\Builder $amounts */
        $amounts = DB::table('cheques')
            ->select(DB::raw("SUM(amount) as value, {$dateColumn} as date"))
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->limit($limit);

        /** @var \Illuminate\Database\Query\Builder $counts */
        $counts = DB::table('cheques')
            ->select(DB::raw("COUNT(*) AS value, {$dateColumn} as date"))
            ->whereNotIn('type_id', [ChequeType::BUY_RETURN, ChequeType::SELL_RETURN])
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->limit($limit);

        if ( ! is_null($mall_id)) {
            $amounts = $amounts->where('mall_id', $mall_id);
            $counts = $counts->where('mall_id', $mall_id);
        }

        $amounts = $amounts->get()->keyBy('date');
        $counts = $counts->get()->keyBy('date');

        $data = [];

        foreach ($amounts as $date => $_data) {
            $data[] = [
                'amount' => $amounts[$date]->value,
                'count' => $counts[$date]->value,
                'avg' => round($amounts[$date]->value / $counts[$date]->value),
                'date' => $date,
            ];
        }

        return collect($data);
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

        /** @var \Illuminate\Database\Query\Builder $amounts */
        $amounts = DB::table('cheques')
            ->select(DB::raw("SUM(amount) AS value, {$dateColumn} as date"))
            ->where('store_id', $store_id)
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->limit($limit)
            ->get()->keyBy('date');

        /** @var \Illuminate\Database\Query\Builder $counts */
        $counts = DB::table('cheques')
            ->select(DB::raw("COUNT(*) AS value, {$dateColumn} as date"))
            ->whereNotIn('type_id', [ChequeType::BUY_RETURN, ChequeType::SELL_RETURN])
            ->where('store_id', $store_id)
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->limit($limit)
            ->get()->keyBy('date');

        $data = [];

        foreach ($amounts as $date => $_data) {
            $data[] = [
                'amount' => $amounts[$date]->value,
                'count' => $counts[$date]->value,
                'avg' => round($amounts[$date]->value / $counts[$date]->value),
                'date' => $date,
            ];
        }

        return collect($data);
    }


    /**
     * @return array
     */
    public static function getAggregatedMonthForMall(): array
    {
        $startedDate = date('Y') . '-' . date('m') . '-01 00:00:00';

        return DB::table('cheques')
            ->select(DB::raw('COUNT(*) AS count, SUM(amount) as amount, mall_id'))
            ->whereNotIn('type_id', [ChequeType::BUY_RETURN, ChequeType::SELL_RETURN])
            ->where('created_at', '>=', $startedDate)
            ->groupBy('mall_id')
            ->get()->keyBy('mall_id')->toArray();
    }


    /**
     * @param int|null $mall_id
     *
     * @return array
     */
    public static function getAggregatedMonthForStore(?int $mall_id): array
    {
        $startedDate = date('Y') . '-' . date('m') . '-01 00:00:00';

        /** @var \Illuminate\Database\Query\Builder $items */
        $items = DB::table('cheques')
            ->select(DB::raw('COUNT(*) AS count, SUM(amount) as amount, store_id'))
            ->whereNotIn('type_id', [ChequeType::BUY_RETURN, ChequeType::SELL_RETURN])
            ->where('created_at', '>=', $startedDate)
            ->groupBy('store_id');

        if ( ! is_null($mall_id)) {
            $items = $items->where('mall_id', $mall_id);
        }

        return $items->get()->keyBy('store_id')->toArray();
    }


    /**
     * @return \Illuminate\Support\Collection
     */
    public static function getCompareForMall(): Collection
    {
        $dateColumn = GraphDate::instance()->getDateColumn();
        $startedDate = GraphDate::instance()->getStartedDate();

        $amounts = Cheque::query()
            ->select(DB::raw("SUM(amount) as value, mall_id, {$dateColumn} as date"))
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
            })->get()->groupBy('date')->toArray();

        $counts = Cheque::query()
            ->select(DB::raw("COUNT(*) as value, mall_id, {$dateColumn} as date"))
            ->whereNotIn('type_id', [ChequeType::BUY_RETURN, ChequeType::SELL_RETURN])
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
            })->get()->groupBy('date')->toArray();

        $data = [];

        foreach ($amounts as $date => $items) {
            foreach ($items as $key => $item) {
                $data[$date][$key] = [
                    'amount' => $amounts[$date][$key]['value'],
                    'count' => $counts[$date][$key]['value'],
                    'avg' => round($amounts[$date][$key]['value'] / $counts[$date][$key]['value']),
                    'date' => $item['date'],
                    'mall_id' => $item['mall_id'],
                ];
            }
        }

        return collect($data);
    }


    /**
     * @return \Illuminate\Support\Collection
     */
    public static function getCompareForStore(): Collection
    {
        $dateColumn = GraphDate::instance()->getDateColumn();
        $startedDate = GraphDate::instance()->getStartedDate();

        $amounts = Cheque::query()
            ->select(DB::raw("SUM(amount) as value, store_id, {$dateColumn} as date"))
            ->whereNotIn('type_id', [ChequeType::BUY_RETURN, ChequeType::SELL_RETURN])
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
            })->get()->groupBy('date')->toArray();

        $counts = Cheque::query()
            ->select(DB::raw("COUNT(*) AS value, store_id, {$dateColumn} as date"))
            ->whereNotIn('type_id', [ChequeType::BUY_RETURN, ChequeType::SELL_RETURN])
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
            })->get()->groupBy('date')->toArray();

        $data = [];

        foreach ($amounts as $date => $items) {
            foreach ($items as $key => $item) {
                $data[$date][$key] = [
                    'amount' => $amounts[$date][$key]['value'],
                    'count' => $counts[$date][$key]['value'],
                    'avg' => round($amounts[$date][$key]['value'] / $counts[$date][$key]['value']),
                    'date' => $item['date'],
                    'store_id' => $item['store_id'],
                ];
            }
        }

        return collect($data);
    }


    /**
     * @param int|null $limit
     *
     * @return \Illuminate\Support\Collection
     */
    public static function getReportForMall(?int $limit = null): Collection
    {
        list($dateFrom, $dateTo, $dateGroup) = ReportDate::instance()->getData();

        /** @var \Illuminate\Database\Query\Builder $items */
        $items = Cheque::reportMall($dateFrom, $dateTo);

        if ( ! is_null($limit)) {
            $items = $items->limit($limit);
        }

        return $items
            ->select(DB::raw("COUNT(*) AS count, SUM(amount) as amount, AVG(amount) as avg, mall_id, created_{$dateGroup} as date"))
            ->whereNotIn('type_id', [ChequeType::BUY_RETURN, ChequeType::SELL_RETURN])
            ->groupBy('date')
            ->groupBy('mall_id')
            ->get();
    }


    /**
     * @param int|null $limit
     *
     * @return \Illuminate\Support\Collection
     */
    public static function getReportForStore(?int $limit = null): Collection
    {
        list($dateFrom, $dateTo, $dateGroup) = ReportDate::instance()->getData();

        /** @var \Illuminate\Database\Query\Builder $items */
        $items = Cheque::reportStore($dateFrom, $dateTo);

        if ( ! is_null($limit)) {
            $items = $items->limit($limit);
        }

        return $items
            ->select(DB::raw("COUNT(*) AS count, SUM(amount) as amount, AVG(amount) as avg, mall_id, store_id, created_{$dateGroup} as date"))
            ->whereNotIn('type_id', [ChequeType::BUY_RETURN, ChequeType::SELL_RETURN])
            ->groupBy('date')
            ->groupBy('store_id')
            ->get();
    }


    /**
     * @param int|null $limit
     *
     * @return \Illuminate\Support\Collection
     */
    public static function getReportDetail(?int $limit = null): Collection
    {
        list($dateFrom, $dateTo, $dateGroup) = ReportDate::instance()->getData();

        /** @var \Illuminate\Database\Query\Builder $items */
        $items = Cheque::reportDetail($dateFrom, $dateTo)->with(['items']);

        if ( ! is_null($limit)) {
            $items = $items->limit($limit);
        }

        return $items->get();
    }


    /**
     * @param string $dateFrom
     * @param string $dateTo
     *
     * @return \Illuminate\Support\Collection|null
     */
    public static function getPlacementForMall(string $dateFrom, string $dateTo): ?Collection
    {
        return Cheque::reportMall($dateFrom, $dateTo)
            ->select(DB::raw('COUNT(*) AS count, SUM(amount) as amount, AVG(amount) as avg, mall_id'))
            ->whereNotIn('type_id', [ChequeType::BUY_RETURN, ChequeType::SELL_RETURN])
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
        return Cheque::reportStore($dateFrom, $dateTo)
            ->select(DB::raw('COUNT(*) AS count, SUM(amount) as amount, AVG(amount) as avg, mall_id, store_id'))
            ->whereNotIn('type_id', [ChequeType::BUY_RETURN, ChequeType::SELL_RETURN])
            ->groupBy('store_id')
            ->get();
    }


    /**
     * @param int    $storeId
     * @param string $date
     *
     * @return bool
     */
    public static function isExistsForDate(int $storeId, string $date): bool
    {
        return Cheque::query()
            ->where('store_id', $storeId)
            ->where('created_at', '>=', "{$date} 00:00:00")
            ->where('created_at', '<=', "{$date} 23:59:59")
            ->exists();
    }

}
