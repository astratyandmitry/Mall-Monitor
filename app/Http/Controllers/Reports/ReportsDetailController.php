<?php

namespace App\Http\Controllers\Reports;

use App\Models\Cheque;

/**
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2018, ArmenianBros. <i@armenianbros.com>
 */
class ReportsDetailController extends \App\Http\Controllers\Controller
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

        $dateFrom = $this->getDate('from');
        $dateTo = $this->getDate('to');

        $builder1 = Cheque::query()->reportDetail($dateFrom, $dateTo);
        $builder2 = clone $builder1;

        $cheques = $builder1->paginate(50)->onEachSide(1);
        $statistics = $builder2->select(\DB::raw('sum(amount) as total, count(id) as count'))->get()->toArray()[0];

        $chequesId = $cheques->map(function (Cheque $cheque) {
            return $cheque->id;
        })->toArray();

        $counts = \DB::table('cheque_items')
            ->select(\DB::raw('count(id) as count, cheque_id'))
            ->groupBy('cheque_id')
            ->whereIn('cheque_id', $chequesId)
            ->pluck('count', 'cheque_id');

        return view('reports.detail.index', $this->withData([
            'statistics' => $statistics,
            'counts' => $counts,
            'cheques' => $cheques,
        ]));
    }


    /**
     * @return string
     */
    public function exportExcel(): string
    {
        $filename = 'mallmonitor_reports.detail_' . date('YmdHi');

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
        $filename = 'mallmonitor_reports.detail_' . date('YmdHi');

        $pdf = \PDF::loadView('reports.detail.export.pdf', $this->getExportData())->setPaper('a4', 'landscape');

        return $pdf->download("{$filename}.pdf");
    }


    /**
     * @return array
     */
    protected function getExportData(): array
    {
        $dateFrom = $this->getDate('from');
        $dateTo = $this->getDate('to');

        $cheques = Cheque::query()->reportDetail($dateFrom, $dateTo)->with(['items'])->get();

        return [
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'cheques' => $cheques,
        ];
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
