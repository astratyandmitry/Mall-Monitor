<?php

namespace App\Http\Controllers\Reports;

use App\Models\Mall;
use App\Repositories\VisitsRepository;
use Illuminate\View\View;
use App\Classes\Date\ReportDate;
use App\Repositories\ChequeRepository;

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
    public function index(): View
    {
        $this->setTitle('Детальный по ТРЦ');
        $this->setActiveSection('reports');
        $this->setActivePage('reports.mall');
        $this->addBreadcrumb('Детальный', route('reports.mall.index'));

        return view('reports.mall.index', $this->withData($this->getData()));
    }

    /**
     * @return string
     */
    public function exportExcel(): string
    {
        $filename = 'keruenmonitor_reports.mall_'.date('YmdHi');

        \Excel::create($filename, function ($excel) {
            $excel->sheet('Отчет по ТРЦ', function ($sheet) {
                $data = $this->getDataForExport($this->getExcelMaxItems());

                $sheet->loadView('reports.mall.export.excel', $data);
            });
        })->export('xls');

        return '<script>window.close();</script>';
    }

    /**
     * @return mixed
     */
    public function exportPDF()
    {
        $filename = 'keruenmonitor_reports.mall_'.date('YmdHi');

        $data = $this->getDataForExport($this->getPDFMaxItems());

        $pdf = \PDF::loadView('reports.mall.export.pdf', $data)->setPaper('a4', 'landscape');

        return $pdf->download("{$filename}.pdf");
    }

    /**
     * @param int|null $limit
     *
     * @return array
     */
    protected function getData(?int $limit = null): array
    {
        $stats = ChequeRepository::getReportForMall($limit);

        $mall_names = Mall::query()->whereIn('id', $stats->pluck('mall_id', 'mall_id'))->pluck('name', 'id');

        $visits = VisitsRepository::getReportForMall();
        $visitSimplified = [];

        foreach ($visits as $visit) {
            $visitSimplified[$visit->date][$visit->mall_id] = $visit->count;
        }

        return [
            'visits' => $visitSimplified,
            'mall_names' => $mall_names,
            'stats' => $stats->toArray(),
        ];
    }

    /**
     * @param int|null $limit
     *
     * @return array
     */
    public function getDataForExport(?int $limit = null): array
    {
        $data = $this->getData($limit);

        $data['selectedMall'] = (request()->has('mall_id')) ? @$data['mall_names'][request()->get('mall_id')] : 'Все';
        $data['selectedTime'] = ReportDate::instance()->stringify();

        return $data;
    }
}
