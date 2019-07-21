<?php

namespace App\Http\Controllers\Reports;

use App\Models\Mall;
use App\Models\Store;
use App\Models\Cheque;

/**
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2018, ArmenianBros. <i@armenianbros.com>
 */
class ReportsStoreController extends Controller
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

        $data = $this->getExportData();

        return view('reports.store.index', $this->withData($data));
    }


    /**
     * @return string
     */
    public function exportExcel(): string
    {
        $filename = 'keruenmonitor_reports.store_' . date('YmdHi');

        \Excel::create($filename, function ($excel) {
            $excel->sheet('Отчет по арендаторам', function ($sheet) {
                $sheet->loadView('reports.store.export.excel', $this->getExportData());
            });
        })->export('xls');

        return '<script>window.close();</script>';
    }


    /**
     * @return mixed
     */
    public function exportPDF()
    {
        $filename = 'keruenmonitor_reports.store_' . date('YmdHi');

        $pdf = \PDF::loadView('reports.store.export.pdf', $this->getExportData($this->getPDFMaxItems()))->setPaper('a4', 'landscape');

        return $pdf->download("{$filename}.pdf");
    }


    /**
     * @param int|null $limit
     *
     * @return array
     */
    protected function getExportData(?int $limit = null): array
    {
        $dateFrom = $this->getDateTime('from');
        $dateTo = $this->getDateTime('to');

        $statistics = Cheque::reportStore($dateFrom, $dateTo);
        $select = 'COUNT(*) AS count, SUM(amount) as amount, AVG(amount) as avg, mall_id, store_id';

        $diff = date_diff(date_create($dateFrom), date_create($dateTo));

        if (( ! $dateFrom && ! $dateTo) || (int)$diff->format("%Y") > 0) {
            $select .= ', YEAR(created_at) as date';

            $statistics = $statistics->groupBy('date');

            $dateGroup = 'year';
        } elseif ((int)$diff->format("%m") > 0) {
            $select .= ', DATE_FORMAT(created_at, "%Y-%m") as date';

            $statistics = $statistics->groupBy('date');

            $dateGroup = 'month';
        } else {
            $select .= ', DATE(created_at) as date';

            $statistics = $statistics->groupBy('date');

            $dateGroup = 'day';
        }

        if ( ! is_null($limit)) {
            $statistics = $statistics->limit($limit);
        }

        $statistics = $statistics->select(\DB::raw($select))->groupBy('store_id')->get();

        $data = [
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'dateGroup' => $dateGroup,
            'statistics' => $statistics->toArray(),
            'mall_names' => Mall::whereIn('id', $statistics->pluck('mall_id'))->pluck('name', 'id'),
            'stores' => Store::whereIn('id', $statistics->pluck('store_id'))->select('name', 'business_identification_number',
                'id')->get()->keyBy('id')->toArray(),
        ];

        return $data;
    }

}
