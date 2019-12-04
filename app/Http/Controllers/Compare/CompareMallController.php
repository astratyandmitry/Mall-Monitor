<?php

namespace App\Http\Controllers\Compare;

use App\Models\Mall;
use App\Models\Cheque;
use Illuminate\Database\Eloquent\Builder;

/**
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2018, ArmenianBros. <i@armenianbros.com>
 */
class CompareMallController extends Controller
{

    /**
     * @return \Illuminate\View\View
     */
    public function index(): \Illuminate\View\View
    {
        $this->setTitle('Сравнение ТРЦ');
        $this->setActiveSection('placement');
        $this->setActivePage('placement.mall');
        $this->addBreadcrumb('Сравнение', route('placement.mall.index'));

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
            ->select(\DB::raw("COUNT(*) AS count, SUM(amount) as amount, AVG(amount) as avg, mall_id, {$graphDateTypes[$graphDateType]} as date"))
            ->where('created_at', '>=', $createdAt)
            ->groupBy('date', 'mall_id')
            ->orderBy('date', 'asc')
            ->where(function (Builder $builder): Builder {
                $builder->when(request('mall_id'), function (Builder $builder): Builder {
                    return $builder->where('mall_id', request('mall_id'));
                });

                $builder->when(request('type_id'), function (Builder $builder): Builder {
                    return $builder->whereHas('mall', function (Builder $builder): Builder {
                        return $builder->where('type_id', request('type_id'));
                    });
                });

                return $builder;
            })->get()->groupBy('date');

        $malls = [];
        $mall_names = Mall::query()->pluck('name', 'id')->toArray();

        $graph = [
            'labels' => [],
            'amount' => [],
            'count' => [],
            'avg' => [],
        ];

        foreach ($statistics as $date => $stats) {
            $graph['labels'][$date] = $this->formatDate($date);

            foreach ($stats as $stat) {
                if (!isset($mall_names[$stat->mall_id])) {
                    continue;
                }

                $malls[$stat->mall_id] = true;

                $graph['amount'][$stat->mall_id][$date] = round($stat->amount);
                $graph['count'][$stat->mall_id][$date] = round($stat->count);
                $graph['avg'][$stat->mall_id][$date] = round($stat->avg);
            }
        }

        return view('compare.mall.index', $this->withData([
            'mall_names' => $mall_names,
            'statistics' => $statistics,
            'graph' => $graph,
        ]));
    }


}
