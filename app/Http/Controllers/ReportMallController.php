<?php

namespace App\Http\Controllers;

use App\Models\Mall;
use App\Models\Cheque;

/**
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2018, ArmenianBros. <i@armenianbros.com>
 */
class ReportMallController extends Controller
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
    public function index(): \Illuminate\View\View
    {
        $this->setTitle('Отчет по ТРЦ');
        $this->setActive('report_mall');

        $dateFrom = $this->getDate('date_from');
        $dateTo = $this->getDate('date_to');

        $statistics = Cheque::reportMall($dateFrom, $dateTo)
            ->select(\DB::raw('COUNT(*) AS count, SUM(amount) as amount, mall_id'))
            ->groupBy('mall_id')->get()->toArray();

        return view('report_mall.index', $this->withData([
            'statistics' => $statistics,
        ]));
    }


    /**
     * @return string
     */
    public function export()
    {
        $dateFrom = $this->getDate('date_from');
        $dateTo = $this->getDate('date_to');

        $statistics = Cheque::reportMall($dateFrom, $dateTo)
            ->select(\DB::raw('COUNT(*) AS count, SUM(amount) as amount, mall_id'))
            ->groupBy('mall_id')->get()->toArray();
        $export = [];

        foreach ($statistics as $statistic) {
            $mall = Mall::find($statistic['mall_id']);

            $export[$mall->id]['ТРЦ'] = $mall->name;
            $export[$mall->id]['Кол-во чеков'] = (int)$statistic['count'];
            $export[$mall->id]['Средний чек'] = (int)round($statistic['amount'] / $statistic['count']);
            $export[$mall->id]['Сумма чеков'] = (int)$statistic['amount'];
        }

        \Excel::create('mallmonitor_report_mall', function ($excel) use ($export) {
            $excel->sheet("Отчет", function ($sheet) use ($export) {
                $sheet->fromArray($export);
            });
        })->export('xls');

        return '<script>window.close();</script>';
    }


    /**
     * @param string $key
     *
     * @return null|string
     */
    protected function getDate(string $key): ?string
    {
        if ($date = \request()->query($key)) {
            return date('Y-m-d H:i:s', strtotime($date));
        }

        return null;
    }

}
