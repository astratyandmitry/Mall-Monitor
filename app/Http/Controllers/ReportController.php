<?php

namespace App\Http\Controllers;

use App\Models\Store;

/**
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2018, ArmenianBros. <i@armenianbros.com>
 */
class ReportController extends Controller
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
        $this->setTitle('Отчет');
        $this->setActive('report');

        $statistics = \DB::table('cheques')
            ->select(\DB::raw('COUNT(*) AS count, SUM(amount) as amount, store_id'))
            ->where('created_at', '>=', date('m') . '-01-' . date('Y') . ' 00:00:00')
            ->where('mall_id', auth()->user()->mall_id)
            ->groupBy('store_id')
            ->get();

        return view('report.index', $this->withData([
            'statistics' => $statistics,
        ]));
    }

}
