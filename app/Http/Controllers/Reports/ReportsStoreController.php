<?php

namespace App\Http\Controllers\Reports;

use App\Models\Cheque;
use App\Models\Store;

/**
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2018, ArmenianBros. <i@armenianbros.com>
 */
class ReportsStoreController extends \App\Http\Controllers\Controller
{

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\View\View
     */
    public function index(\Illuminate\Http\Request $request): \Illuminate\View\View
    {
        $this->setTitle('Отчет по арендаторам');
        $this->setActiveSection('reports');
        $this->setActivePage('reports.store');
        $this->addBreadcrumb('Отчеты', route('reports.store.index'));

        $dateFrom = $this->getDate('date_from');
        $dateTo = $this->getDate('date_to');

        $statistics = Cheque::reportStore($dateFrom, $dateTo)
            ->select(\DB::raw('COUNT(*) AS count, SUM(amount) as amount, store_id'))
            ->groupBy('store_id')->get()->toArray();

        return view('reports.store.index', $this->withData([
            'statistics' => $statistics,
        ]));
    }


    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return string
     */
    public function exportExcel(\Illuminate\Http\Request $request)
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

        \Excel::create('mallmonitor_reports.store', function ($excel) use ($export) {
            $excel->sheet("Отчет", function ($sheet) use ($export) {
                $sheet->fromArray($export);
            });
        })->export('xls');

        return '<script>window.close();</script>';
    }


    public function exportPDF()
    {

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
