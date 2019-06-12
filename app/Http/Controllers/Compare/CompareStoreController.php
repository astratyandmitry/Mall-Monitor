<?php

namespace App\Http\Controllers\Compare;

use App\Models\Cheque;
use App\Models\Store;
use Illuminate\Database\Eloquent\Builder;

/**
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2018, ArmenianBros. <i@armenianbros.com>
 */
class CompareStoreController extends Controller
{

    /**
     * @return \Illuminate\View\View
     */
    public function index(): \Illuminate\View\View
    {
        $this->setTitle('Сравнение арендаторов');
        $this->setActiveSection('compare');
        $this->setActivePage('compare.store');
        $this->addBreadcrumb('Сравнение', route('compare.store.index'));

        $graphDateTypes = [
            'daily' => 'DATE(created_at)',
            'monthly' => 'DATE_FORMAT(created_at, "%Y-%m")',
            'yearly' => 'YEAR(created_at)',
        ];

        $graphDateType = (request('graph_date_type') && in_array(request('graph_date_type'),
                array_keys($graphDateTypes))) ? request('graph_date_type') : 'daily';

        switch ($graphDateType) {
            case 'daily':
                $createdAt = date('Y-m-d H:i:s', strtotime('-30 days'));
                break;
            case 'monthly':
                $createdAt = date('Y-m-d H:i:s', strtotime('-12 months'));
                break;
            case 'yearly':
                $createdAt = date('Y-m-d H:i:s', strtotime('-10 years'));
                break;
        }

        $statistics = Cheque::query()
            ->select(\DB::raw("COUNT(*) AS count, SUM(amount) as amount, AVG(amount) as avg, store_id, {$graphDateTypes[$graphDateType]} as date"))
            ->where('created_at', '>=', $createdAt)
            ->groupBy('date', 'store_id')
            ->orderBy('date', 'asc')
            ->where(function (Builder $builder): Builder {
                $builder->when(request('mall_id'), function (Builder $builder): Builder {
                    return $builder->where('mall_id', request('mall_id'));
                });

                $builder->when(request('type_id'), function (Builder $builder): Builder {
                    return $builder->whereHas('store', function (Builder $builder): Builder {
                        return $builder->where('type_id', request('type_id'));
                    });
                });

                return $builder;
            })->get()->groupBy('date');

        $stores = [];

        $graph = [
            'labels' => [],
            'amount' => [],
            'count' => [],
            'avg' => [],
        ];

        foreach ($statistics as $date => $stats) {
            $graph['labels'][$date] = $this->formatDate($date);

            foreach ($stats as $stat) {
                $stores[$stat->store_id] = true;

                $graph['amount'][$stat->store_id][$date] = round($stat->amount);
                $graph['count'][$stat->store_id][$date] = round($stat->count);
                $graph['avg'][$stat->store_id][$date] = round($stat->avg);
            }
        }

        return view('compare.store.index', $this->withData([
            'store_names' => Store::whereIn('id', array_keys($stores))->pluck('name', 'id')->toArray(),
            'statistics' => $statistics,
            'graph' => $graph,
        ]));
    }

}
