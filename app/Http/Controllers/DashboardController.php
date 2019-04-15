<?php

namespace App\Http\Controllers;

use App\Models\Cheque;

/**
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2018, ArmenianBros. <i@armenianbros.com>
 */
class DashboardController extends Controller
{

    /**
     * DashboardController constructor.
     */
    public function __construct()
    {
        $this->middleware('loggined');
    }


    /**
     * @return \Illuminate\View\View
     */
    public function __invoke(): \Illuminate\View\View
    {
        $this->setTitle('Обзор');
        $this->setActiveSection('dashboard');
        $this->setActivePage('dashboard');

        if (auth()->user()->store_id) {
            return $this->forStore();
        }

        return $this->forMall();
    }


    /**
     * @return \Illuminate\View\View
     */
    protected function forStore(): \Illuminate\View\View
    {
        $store = auth()->user()->store;

        $graphDateTypes = [
            'daily' => 'DATE(created_at)',
            'monthly' => 'DATE_FORMAT(created_at, "%Y-%m")',
            'yearly' => 'YEAR(created_at)',
        ];

        $graphDateType = (request('graph_date_type') && in_array(request('graph_date_type'),
                array_keys($graphDateTypes))) ? request('graph_date_type') : 'daily';

        $statistics = \DB::table('cheques')
            ->select(\DB::raw("COUNT(*) AS count, SUM(amount) as amount, AVG(amount) as avg, {$graphDateTypes[$graphDateType]} as date"))
            ->where('store_id', $store->id)
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->limit(30)
            ->get();

        $graph = [
            'labels' => [],
            'amount' => [],
            'count' => [],
            'avg' => [],
        ];

        foreach ($statistics as $statistic) {
            $graph['labels'][] = $this->formatDate($statistic->date);
            $graph['amount'][] = round($statistic->amount);
            $graph['count'][] = round($statistic->count);
            $graph['avg'][] = round($statistic->avg);
        }

        $graph['labels'] = array_reverse($graph['labels']);
        $graph['amount'] = array_reverse($graph['amount']);
        $graph['count'] = array_reverse($graph['count']);
        $graph['avg'] = array_reverse($graph['avg']);

        $today = date('Y-m-d');

        return view('stores.show', $this->withData([
            'graph' => $graph,
            'store' => $store,
            'statistics' => $statistics,
        ]));
    }


    /**
     * @return \Illuminate\View\View
     */
    protected function forMall(): \Illuminate\View\View
    {
        $graphDateTypes = [
            'daily' => 'DATE(created_at)',
            'monthly' => 'DATE_FORMAT(created_at, "%Y-%m")',
            'yearly' => 'YEAR(created_at)',
        ];

        $graphDateType = (request('graph_date_type') && in_array(request('graph_date_type'),
                array_keys($graphDateTypes))) ? request('graph_date_type') : 'daily';

        $today = date('Y-m-d');

        $statistics = \DB::table('cheques')
            ->select(\DB::raw("COUNT(*) AS count, SUM(amount) as amount, AVG(amount) as avg, {$graphDateTypes[$graphDateType]} as date"))
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->limit(30);
        $cheques = Cheque::where('created_at', 'LIKE', '%' . $today . '%');

        if (auth()->user()->mall_id) {
            $statistics = $statistics->where('mall_id', auth()->user()->mall_id);
            $cheques = $cheques->where('mall_id', auth()->user()->mall_id);
        }

        $statistics = $statistics->get();
        $cheques = $cheques->latest()->limit(25)->get();

        $graph = [
            'labels' => [],
            'amount' => [],
            'count' => [],
            'avg' => [],
        ];

        foreach ($statistics as $statistic) {
            $graph['labels'][] = $this->formatDate($statistic->date);
            $graph['amount'][] = round($statistic->amount);
            $graph['count'][] = round($statistic->count);
            $graph['avg'][] = round($statistic->avg);
        }

        $graph['labels'] = array_reverse($graph['labels']);
        $graph['amount'] = array_reverse($graph['amount']);
        $graph['count'] = array_reverse($graph['count']);
        $graph['avg'] = array_reverse($graph['avg']);

        $pies = [];
        $data = \DB::table('store_types')
            ->select(\DB::raw('SUM(`cheques`.`amount`) as `total`, `store_types`.`name` as `name`, `store_types`.`color` as `color`'))
            ->leftJoin('stores', 'stores.type_id', '=', 'store_types.id')
            ->leftJoin('cheques', 'cheques.store_id', '=', 'stores.id')
            ->where('cheques.created_at', 'LIKE', date('Y-m-d') . '%')
            ->groupBy('store_types.id')
            ->get();

        if (count($data)) {
            foreach ($data as $item) {
                $pies['totals'][] = (int)$item->total;
                $pies['colors'][] = "#{$item->color}";
                $pies['names'][] = $item->name;
            }
        }

        return view('dashboard.index', $this->withData([
            'graph' => $graph,
            'pies' => $pies,
            'statistics' => $statistics,
            'cheques' => $cheques,
        ]));
    }

}
