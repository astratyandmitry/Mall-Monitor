<?php

namespace App\Http\Controllers\Reports;

use App\Models\Cheque;

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
    public function index(): \Illuminate\View\View
    {
        $this->setTitle('Детальный отчет');
        $this->setActiveSection('reports');
        $this->setActivePage('reports.detail');
        $this->addBreadcrumb('Отчеты', route('reports.detail.index'));

        $dateFrom = $this->getDateTime('from');
        $dateTo = $this->getDateTime('to');

        $cheques = Cheque::query()->reportDetail($dateFrom, $dateTo)->paginate(50)->onEachSide(1);

        $chequesId = $cheques->map(function (Cheque $cheque) {
            return $cheque->id;
        })->toArray();

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
                $sheet->loadView('reports.detail.export.excel', $this->getExportData());
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

        $pdf = \PDF::loadView('reports.detail.export.pdf', $this->getExportData($this->getPDFMaxItems()))->setPaper('a4', 'landscape');

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

        $cheques = Cheque::query()->reportDetail($dateFrom, $dateTo)->with(['items']);

        if ( ! is_null($limit)) {
            $cheques = $cheques->limit($limit);
        }

        $cheques = $cheques->get();

        return [
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'cheques' => $cheques,
        ];
    }

}
