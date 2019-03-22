<?php

namespace App\Http\Controllers;

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
        $this->addBreadcrumb('Обзор', route('dashboard'));

        $statistics = \DB::table('cheques')
            ->select(\DB::raw('COUNT(*) AS count, SUM(amount) as amount, DATE(created_at) as date'))
            ->where('mall_id', auth()->user()->mall_id)
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->limit(10)
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
            $graph['avg'][] = round($statistic->amount / $statistic->count);
        }

        $graph['labels'] = array_reverse($graph['labels']);
        $graph['amount'] = array_reverse($graph['amount']);
        $graph['count'] = array_reverse($graph['count']);
        $graph['avg'] = array_reverse($graph['avg']);

        $today = date('Y-m-d');

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
            'cheques' => auth()->user()->mall->cheques()->where('created_at', 'LIKE', '%' . $today . '%')->latest()->limit(25)->get(),
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

        return (int)$dates[2] . " " . $months[(int)$dates[1]] . " {$dates[0]}";
    }

}
