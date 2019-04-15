<?php

namespace App\Http\Controllers;

use App\Models\Mall;

/**
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2018, ArmenianBros. <i@armenianbros.com>
 */
class MallsController extends Controller
{

    public function __construct()
    {
        $this->middleware('not-mall');
    }


    /**
     * @return \Illuminate\View\View
     */
    public function index(): \Illuminate\View\View
    {
        $this->setTitle('ТРЦ');
        $this->setActiveSection('malls');
        $this->setActivePage('malls');

        $statistics = \DB::table('cheques')
            ->select(\DB::raw('COUNT(*) AS count, SUM(amount) as amount, mall_id'))
            ->where('created_at', '>=', date('Y') . '-' . date('m') . '-01' . ' 00:00:00')
            ->groupBy('mall_id')
            ->pluck('amount', 'mall_id')->toArray();

        $stores = Mall::orderBy('name')->get();

        return view('malls.index', $this->withData([
            'currentMonth' => \App\DateHelper::getMonthFull(),
            'statistics' => $statistics,
            'malls' => $stores,
        ]));
    }


    /**
     * @param \App\Models\Mall $mall
     *
     * @return \Illuminate\View\View
     */
    public function show(Mall $mall): \Illuminate\View\View
    {
        $user = auth()->user();

        abort_if($user->mall_id, 404);

        $this->setTitle($mall->name);
        $this->setActiveSection('malls');
        $this->setActivePage('malls');
        $this->addBreadcrumb('ТРЦ', route('malls.index'));

        $graphDateTypes = [
            'daily' => 'DATE(created_at)',
            'monthly' => 'DATE_FORMAT(created_at, "%Y-%m")',
            'yearly' => 'YEAR(created_at)',
        ];

        $graphDateType = (request('graph_date_type') && in_array(request('graph_date_type'),
                array_keys($graphDateTypes))) ? request('graph_date_type') : 'daily';

        $statistics = \DB::table('cheques')
            ->select(\DB::raw("COUNT(*) AS count, SUM(amount) as amount, AVG(amount) as avg, {$graphDateTypes[$graphDateType]} as date"))
            ->where('mall_id', $mall->id)
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

        return view('malls.show', $this->withData([
            'graph' => $graph,
            'mall' => $mall,
            'statistics' => $statistics,
            'cheques' => $mall->cheques()->where('created_at', 'LIKE', '%' . $today . '%')->latest()->limit(100)->get(),
        ]));
    }

}
