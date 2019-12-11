<?php

namespace App\Http\Controllers\Reports;

use App\Models\Mall;
use App\Models\Store;
use Illuminate\View\View;
use App\Classes\ReportDate;
use App\Repositories\ChequeRepository;

/**
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2018, ArmenianBros. <i@armenianbros.com>
 */
class ReportsStoreController extends Controller
{

    /**
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        $this->setTitle('Отчет по арендаторам');
        $this->setActiveSection('reports');
        $this->setActivePage('reports.store');
        $this->addBreadcrumb('Отчеты', route('reports.store.index'));

        return view('reports.store.index', $this->withData($this->getData()));
    }


    /**
     * @return string
     */
    public function exportExcel(): string
    {
        $filename = 'keruenmonitor_reports.store_' . date('YmdHi');

        \Excel::create($filename, function ($excel) {
            $excel->sheet('Отчет по арендаторам', function ($sheet) {
                $data = $this->getDataForExport($this->getExcelMaxItems());

                $sheet->loadView('reports.store.export.excel', $data);
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

        $data = $this->getDataForExport($this->getPDFMaxItems());

        $pdf = \PDF::loadView('reports.store.export.pdf', $data)->setPaper('a4', 'landscape');

        return $pdf->download("{$filename}.pdf");
    }


    /**
     * @param int|null $limit
     *
     * @return array
     */
    protected function getData(?int $limit = null): array
    {
        $stats = ChequeRepository::getReportForStore($limit);

        $mall_names = Mall::query()->whereIn('id', $stats->pluck('mall_id'))->pluck('name', 'id');
        $stores = Store::query()
            ->whereIn('id', $stats->pluck('store_id'))
            ->select('name', 'business_identification_number', 'id')
            ->get()->keyBy('id')->toArray();

        return [
            'stats' => $stats->toArray(),
            'mall_names' => $mall_names,
            'stores' => $stores,
        ];
    }


    /**
     * @param int|null $limit
     *
     * @return array
     */
    protected function getDataForExport(?int $limit = null): array
    {
        $data = $this->getData($limit);

        $data['selectedMall'] = (request()->has('mall_id')) ? $data['mall_names'][request()->get('mall_id')] : 'Все';
        $data['selectedStore'] = (request()->has('store_id')) ? @$data['stores'][request()->get('store_id')]['name'] : 'Все';
        $data['selectedTime'] = ReportDate::instance()->stringify();

        return $data;
    }

}
