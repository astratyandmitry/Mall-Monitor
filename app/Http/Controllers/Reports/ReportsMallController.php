<?php

namespace App\Http\Controllers\Reports;

use App\Classes\ReportDate;
use App\Models\Mall;
use App\Models\Cheque;

/**
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2018, ArmenianBros. <i@armenianbros.com>
 */
class ReportsMallController extends Controller
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
        $filename = 'keruenmonitor_reports.mall_' . date('YmdHi');

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
        $filename = 'keruenmonitor_reports.mall_' . date('YmdHi');

        $pdf = \PDF::loadView('reports.mall.export.pdf', $this->getExportData($this->getPDFMaxItems()))->setPaper('a4', 'landscape');

        return $pdf->download("{$filename}.pdf");
    }


    /**
     * @param int|null $limit
     *
     * @return array
     */
    protected function getExportData(?int $limit = null): array
    {
        $dateFrom = ReportDate::getFromRequest('from');
        $dateTo = ReportDate::getFromRequest('to');

        /** @var \Illuminate\Database\Query\Builder */
        $statistics = Cheque::reportMall($dateFrom, $dateTo);
        $select = 'COUNT(*) AS count, SUM(amount) as amount, AVG(amount) as avg, mall_id';

        $diff = date_diff(date_create($dateFrom), date_create($dateTo));

        if (( ! $dateFrom && ! $dateTo) || (int)$diff->format("%Y") > 0) {
            $select .= ', created_year as date';

            $statistics = $statistics->groupBy('date');

            $dateGroup = 'year';
        } elseif ((int)$diff->format("%m") > 0) {
            $select .= ', created_yearmonth as date';

            $statistics = $statistics->groupBy('date');

            $dateGroup = 'month';
        } else {
            $select .= ', created_date as date';

            $statistics = $statistics->groupBy('date');

            $dateGroup = 'day';
        }

        if ( ! is_null($limit)) {
            $statistics = $statistics->limit($limit);
        }

        $statistics = $statistics->select(\DB::raw($select))->groupBy('mall_id')->get();

        $data = [
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'dateGroup' => $dateGroup,
            'statistics' => $statistics->toArray(),
            'mall_names' => Mall::query()->whereIn('id', $statistics->pluck('mall_id', 'mall_id'))->pluck('name', 'id'),
        ];

        return $data;
    }

}
