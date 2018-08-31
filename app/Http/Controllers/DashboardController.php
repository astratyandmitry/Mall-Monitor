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
        $this->setActive('dashboard');

        $statistics = \DB::table('cheques')
            ->select(\DB::raw('COUNT(*) AS count, SUM(amount) as amount, DATE(created_at) as date'))
            ->where('mall_id', auth()->user()->mall_id)
            ->groupBy(\DB::raw('DATE(created_at)'))
            ->orderBy('date', 'desk')
            ->limit(10)
            ->get();

        $graph = [
            'labels' => [],
            'values' => [],
        ];

        foreach ($statistics as $statistic) {
            $graph['labels'][] = date('d.m.Y', strtotime($statistic->date));
            $graph['values'][] = round($statistic->amount);
        }

        $graph['labels'] = array_reverse($graph['labels']);
        $graph['values'] = array_reverse($graph['values']);

        $today = (env('APP_ENV') == 'local') ? '2018-08-23' : date('Y-m-d');

        return view('dashboard.index', $this->withData([
            'graph' => $graph,
            'statistics' => $statistics,
            'cheques' => auth()->user()->mall->cheques()->where('created_at', 'LIKE', '%' . $today . '%')->latest()->paginate(25),
        ]));

        return view('dashboard.index', $this->withData());
    }

}
