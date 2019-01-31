<?php

namespace App\Http\Controllers;

use App\Models\Cheque;
use App\Models\Store;

/**
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2018, ArmenianBros. <i@armenianbros.com>
 */
class ReportStoreController extends Controller
{

    /**
     * DashboardController constructor.
     */
    public function __construct()
    {
        $this->middleware('loggined');
    }


    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\View\View
     */
    public function index(\Illuminate\Http\Request $request): \Illuminate\View\View
    {
        $this->setTitle('Детальный отчет');
        $this->setActive('report_store');

        $dateFrom = $this->getDate('date_from');
        $dateTo = $this->getDate('date_to');

        $statistics = Cheque::reportStore($dateFrom, $dateTo)
            ->select(\DB::raw('COUNT(*) AS count, SUM(amount) as amount, store_id'))
            ->groupBy('store_id')->get()->toArray();

        return view('report_store.index', $this->withData([
            'statistics' => $statistics,
        ]));
    }


    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return string
     */
    public function export(\Illuminate\Http\Request $request)
    {
        $dateFrom = $this->getDate('date_from');
        $dateTo = $this->getDate('date_to');

        $statistics = Cheque::reportStore($dateFrom, $dateTo)
            ->select(\DB::raw('COUNT(*) AS count, SUM(amount) as amount, store_id'))
            ->groupBy('store_id')->get()->toArray();

        $export = [];
        foreach ($statistics as $statistic) {
            $store = Store::find($statistic['store_id']);

            $export[$store->id]['ТРЦ'] = $store->mall->name;
            $export[$store->id]['Заведение'] = $store->name;
            $export[$store->id]['Кол-во чеков'] = (int)$statistic['count'];
            $export[$store->id]['Средний чек'] = (int)round($statistic['amount'] / $statistic['count']);
            $export[$store->id]['Сумма чеков'] = (int)$statistic['amount'];
        }

        \Excel::create('mallmonitor_report_store', function ($excel) use ($export) {
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
