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

    public function __construct()
    {
        $this->middleware('not-store');
    }


    /**
     * @return \Illuminate\View\View
     */
    public function index(): \Illuminate\View\View
    {
        $this->setTitle('Арендаторы');
        $this->setActiveSection('stores');
        $this->setActivePage('stores');

        $statistics = \DB::table('cheques')
            ->select(\DB::raw('COUNT(*) AS count, SUM(amount) as amount, store_id'))
            ->where('created_at', '>=', date('Y') . '-' . date('m') . '-01' . ' 00:00:00')
            ->groupBy('store_id');

        $stores = Store::orderBy('name');

        if (auth()->user()->mall_id) {
            $statistics = $statistics->where('mall_id', auth()->user()->mall_id);
            $stores = $stores->where('mall_id', auth()->user()->mall_id);
        }

        $statistics = $statistics->pluck('amount', 'store_id')->toArray();
        $stores = $stores->get();

        return view('stores.index', $this->withData([
            'statistics' => $statistics,
            'stores' => $stores,
        ]));
    }


    /**
     * @param \App\Models\Store $store
     *
     * @return \Illuminate\View\View
     */
    public function show(Store $store): \Illuminate\View\View
    {
        $user = auth()->user();

        abort_if($user->mall_id && $user->mall_id != $store->mall_id, 404);

        $this->setTitle($store->name);
        $this->setActiveSection('stores');
        $this->setActivePage('stores');
        $this->addBreadcrumb('Арендаторы', route('stores.index'));

        $graphDateTypes = [
            'daily' => 'DATE(created_at)',
            'monthly' => 'CONCAT(YEAR(created_at),"-",MONTH(created_at))',
            'yearly' => 'YEAR(created_at)',
        ];

        $graphDateType = (request('graph_date_type') && in_array(request('graph_date_type'),
                array_keys($graphDateTypes))) ? request('graph_date_type') : 'daily';

        $statistics = \DB::table('cheques')
            ->select(\DB::raw("COUNT(*) AS count, SUM(amount) as amount, AVG(amount) as avg, {$graphDateTypes[$graphDateType]} as date"))
            ->where('store_id', $store->id)
            ->groupBy('date')
            ->orderBy('date', 'desk')
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
            $graph['avg'][] = $statistic->avg;
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
            'cheques' => $store->cheques()->where('created_at', 'LIKE', '%' . $today . '%')->latest()->limit(100)->get(),
        ]));
    }


    /**
     * @param string $date
     *
     * @return string
     */
    protected function formatDate(string $date): string
    {
        $dates = explode('-', $date);

        if (count($dates) == 1) {
            return $date;
        }

        $months = [
            1 => 'янв.',
            2 => 'фев.',
            3 => 'мар.',
            4 => 'апр.',
            5 => 'май.',
            6 => 'июн.',
            7 => 'июл.',
            8 => 'авг.',
            9 => 'сен.',
            10 => 'окт.',
            11 => 'ноя.',
            12 => 'дек.',
        ];

        if (count($dates) == 2) {
            return $months[(int)$dates[1]] . " {$dates[0]}";
        }

        $days = [
            1 => 'Пн.',
            2 => 'Вт.',
            3 => 'Ср.',
            4 => 'Чт.',
            5 => 'Пт.',
            6 => 'Сб.',
            7 => 'Вс.',
        ];

        $day = $days[date('N', strtotime($date))];

        return (int)$dates[2] . " " . $months[(int)$dates[1]] . " {$dates[0]} ({$day})";
    }

}
