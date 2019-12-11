<?php

namespace App\Http\Controllers\Reports;

use App\Models\Mall;
use Illuminate\View\View;
use App\Classes\ReportDate;
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
        $this->setTitle('Отчет по ТРЦ');
        $this->setActiveSection('reports');
        $this->setActivePage('reports.mall');
        $this->addBreadcrumb('Отчеты', route('reports.mall.index'));

        return view('reports.mall.index', $this->withData($this->getData()));
    }


    /**
     * @return string
     */
    public function exportExcel(): string
    {
        $filename = 'keruenmonitor_reports.mall_' . date('YmdHi');

        \Excel::create($filename, function ($excel) {
            $excel->sheet('Отчет по ТРЦ', function ($sheet) {
                $sheet->loadView('reports.mall.export.excel', $this->getDataForExport());
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

        $mall_names = Mall::query()->whereIn('id', $stats->pluck('mall_id'))->pluck('name', 'id');

        return [
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
