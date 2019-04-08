<?php

namespace App\Http\Controllers\Reports;

use App\Models\Mall;
use App\Models\Cheque;

/**
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2018, ArmenianBros. <i@armenianbros.com>
 */
class ReportsMallController extends \App\Http\Controllers\Controller
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
        $this->setTitle('Отчет по ТРЦ');
        $this->setActiveSection('reports');
        $this->setActivePage('reports.mall');
        $this->addBreadcrumb('Отчеты', route('reports.mall.index'));

        $data = $this->getExportData();

        return view('reports.mall.index', $this->withData($data));
    }


    /**
     * @return string
     */
    public function exportExcel(): string
    {
        $filename = 'mallmonitor_reports.mall_' . date('YmdHi');

        \Excel::create($filename, function ($excel) {
            $excel->sheet('Отчет по ТРЦ', function ($sheet) {
                $sheet->loadView('reports.mall.export.excel', $this->getExportData());
            });
        })->export('xls');

        return '<script>window.close();</script>';
    }


    /**
     * @return mixed
     */
    public function exportPDF()
    {
        $filename = 'mallmonitor_reports.mall_' . date('YmdHi');

        $pdf = \PDF::loadView('reports.mall.export.pdf', $this->getExportData())->setPaper('a4', 'landscape');

        return $pdf->download("{$filename}.pdf");
    }


    /**
     * @return array
     */
    protected function getExportData(): array
    {
        $dateFrom = $this->getDate('from');
        $dateTo = $this->getDate('to');

        $statistics = Cheque::reportMall($dateFrom, $dateTo);
        $select = 'COUNT(*) AS count, SUM(amount) as amount, AVG(amount) as avg, mall_id';

        $isGroupByDates = false;

        if ($dateFrom && $dateTo) {
            $diff = date_diff(date_create($dateFrom), date_create($dateTo));

            if ($diff->format("%a") <= 90) {
                $select .= ', DATE(created_at) as date';

                $statistics = $statistics->groupBy('date');

                $isGroupByDates = true;
            }
        }

        $statistics = $statistics->select(\DB::raw($select))->groupBy('mall_id')->get();

        $data = [
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'isGroupByDates' => $isGroupByDates,
            'statistics' => $statistics->toArray(),
            'mall_names' => Mall::whereIn('id', $statistics->pluck('mall_id'))->pluck('name', 'id'),
        ];

        return $data;
    }


    /**
     * @param string $key
     *
     * @return null|string
     */
    protected function getDate(string $key): ?string
    {
        if ($date = request()->query("date_{$key}")) {
            $time = request()->query("time_{$key}");

            if ( ! $time) {
                $time = ($key == 'from') ? '00:00' : '23:59';
            }

            request()->merge([
                "time_{$key}" => $time,
            ]);

            return date('Y-m-d H:i:s', strtotime("{$date} {$time}"));
        }

        return null;
    }

}
