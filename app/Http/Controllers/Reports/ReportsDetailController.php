<?php

namespace App\Http\Controllers\Reports;

use App\Models\Mall;
use App\Models\Store;
use App\Models\Cheque;
use Illuminate\View\View;
use App\Classes\ReportDate;
use App\Repositories\ChequeRepository;

/**
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2018, ArmenianBros. <i@armenianbros.com>
 */
class ReportsDetailController extends Controller
{

    /**
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        $this->setTitle('Детальный отчет');
        $this->setActiveSection('reports');
        $this->setActivePage('reports.detail');
        $this->addBreadcrumb('Отчеты', route('reports.detail.index'));

        $cheques = Cheque::reportDetail(
            ReportDate::instance()->getDateFrom(), ReportDate::instance()->getDateTo()
        )->paginate(50)->onEachSide(1);

        return view('reports.detail.index', $this->withData([
            'cheques' => $cheques,
        ]));
    }


    /**
     * @return string
     */
    public function exportExcel(): string
    {
        $filename = 'keruenmonitor_reports.detail_' . date('YmdHi');

        \Excel::create($filename, function ($excel) {
            $excel->sheet('Детальный отчет', function ($sheet) {
                $data = $this->getDataForExport($this->getExcelMaxItems());

                $sheet->loadView('reports.detail.export.excel', $data);
            });
        })->export('xls');

        return '<script>window.close();</script>';
    }


    /**
     * @return mixed
     */
    public function exportPDF()
    {
        $filename = 'keruenmonitor_reports.detail_' . date('YmdHi');

        $data = $this->getDataForExport($this->getPDFMaxItems());

        $pdf = \PDF::loadView('reports.detail.export.pdf', $data)->setPaper('a4', 'landscape');

        return $pdf->download("{$filename}.pdf");
    }


    /**
     * @param int|null $limit
     *
     * @return array
     */
    protected function getDataForExport(?int $limit = null): array
    {
        $cheques = ChequeRepository::getReportDetail($limit);

        /** @var \App\Models\Store $selected_store */
        $selected_store = Store::query()->find(request()->get('store_id'));

        /** @var \App\Models\Mall $selected_mall */
        $selected_mall = Mall::query()->find(request()->get('store_id'));

        return [
            'selectedTime' => ReportDate::instance()->stringify(),
            'selectedStore' => ($selected_store) ? $selected_store->name : 'Все',
            'selectedMall' => ($selected_mall) ? $selected_mall->name : 'Все',
            'cheques' => $cheques,
        ];
    }

}
