<?php

namespace App\Http\Controllers;

use App\Models\Store;

/**
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2018, ArmenianBros. <i@armenianbros.com>
 */
class StoresController extends Controller
{

    /**
     * @return \Illuminate\View\View
     */
    public function index(): \Illuminate\View\View
    {
        $this->setTitle('Заведения');
        $this->setActive('stores');

        $statistics = \DB::table('cheques')
            ->select(\DB::raw('COUNT(*) AS count, SUM(amount) as amount, store_id'))
            ->where('created_at', '>=', date('m') . '-01-' . date('Y') . ' 00:00:00')
            ->where('mall_id', auth()->user()->mall_id)
            ->groupBy('store_id')
            ->pluck('amount', 'store_id')
            ->toArray();

        return view('stores.index', $this->withData([
            'statistics' => $statistics,
            'stores' => Store::where('mall_id', auth()->user()->mall_id)->orderBy('name')->get(),
        ]));
    }


    /**
     * @param \App\Models\Store $store
     *
     * @return \Illuminate\View\View
     */
    public function show(Store $store): \Illuminate\View\View
    {
        $this->setTitle($store->name);
        $this->setActive('stores');

        $statistics = \DB::table('cheques')
            ->select(\DB::raw('COUNT(*) AS count, SUM(amount) as amount, DATE(created_at) as date'))
            ->where('store_id', $store->id)
            ->groupBy(\DB::raw('DATE(created_at)'))
            ->orderBy('date', 'desk')
            ->limit(10)
            ->get();

        $graph = [
            'labels' => [],
            'amount' => [],
            'count' => [],
            'avg' => [],
        ];

        foreach ($statistics as $statistic) {
            $graph['labels'][] = date('d.m.Y', strtotime($statistic->date));
            $graph['amount'][] = round($statistic->amount);
            $graph['count'][] = round($statistic->count);
            $graph['avg'][] = round($statistic->amount / $statistic->count);
        }

        $graph['labels'] = array_reverse($graph['labels']);
        $graph['amount'] = array_reverse($graph['amount']);
        $graph['count'] = array_reverse($graph['count']);
        $graph['avg'] = array_reverse($graph['avg']);

        $today = (env('APP_ENV') == 'local') ? '2018-08-23' : date('Y-m-d');

        return view('stores.show', $this->withData([
            'graph' => $graph,
            'store' => $store,
            'statistics' => $statistics,
            'cheques' => $store->cheques()->where('created_at', 'LIKE', '%' . $today . '%')->latest()->paginate(25),
        ]));
    }

}
