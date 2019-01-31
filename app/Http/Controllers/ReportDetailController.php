<?php

namespace App\Http\Controllers;

use App\Models\Cheque;

/**
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2018, ArmenianBros. <i@armenianbros.com>
 */
class ReportDetailController extends Controller
{

    /**
     * DashboardController constructor.
     */
    public function __construct()
    {
        $this->middleware('loggined');
    }


    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\View\View
     */
    public function index(\Illuminate\Http\Request $request): \Illuminate\View\View
    {
        $this->setTitle('Детальный отчет');
        $this->setActive('report_detail');

        $dateFrom = $this->getDate('date_from');
        $dateTo = $this->getDate('date_to');

        $builder1 = Cheque::query()->reportDetail($dateFrom, $dateTo);
        $builder2 = clone $builder1;

        $cheques = $builder1->paginate(250);
        $statistic = $builder2->select(\DB::raw('sum(amount) as total, count(id) as count'))->get()->toArray()[0];

        $chequesId = $cheques->map(function (Cheque $cheque) {
            return $cheque->id;
        })->toArray();

        $counts = [];
        $chequeCounts = \DB::table('cheque_items')
            ->select(\DB::raw('count(id) as count, sum(quantity) as quantity, cheque_id'))
            ->groupBy('cheque_id')
            ->whereIn('cheque_id', $chequesId)
            ->get();

        foreach ($chequeCounts as $chequeCount) {
            $counts[$chequeCount->cheque_id] = [
                'count' => $chequeCount->count,
                'quantity' => $chequeCount->quantity
            ];
        }

        return view('report_detail.index', $this->withData([
            'statistic' => $statistic,
            'counts' => $counts,
            'cheques' => $cheques,
        ]));
    }


    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return string
     */
    public function export(\Illuminate\Http\Request $request)
    {
        $dateFrom = $this->getDate('date_from');
        $dateTo = $this->getDate('date_to');

        $filename = "mallmonitor_report_detail";
        $cheques = Cheque::query()->reportDetail($dateFrom, $dateTo)->get();

        $chequesId = $cheques->map(function (Cheque $cheque) {
            return $cheque->id;
        })->toArray();

        $counts = [];
        $chequeCounts = \DB::table('cheque_items')
            ->select(\DB::raw('count(id) as count, sum(quantity) as quantity, cheque_id'))
            ->groupBy('cheque_id')
            ->whereIn('cheque_id', $chequesId)
            ->get();

        foreach ($chequeCounts as $chequeCount) {
            $counts[$chequeCount->cheque_id] = [
                'count' => $chequeCount->count,
                'quantity' => $chequeCount->quantity
            ];
        }

        $export = [];
        foreach ($cheques as $cheque) {
            $export[$cheque->id]['Заведение'] = $cheque->store->name;
            $export[$cheque->id]['Код касссы'] = $cheque->kkm_code;
            $export[$cheque->id]['Номер документа'] = $cheque->number;
            $export[$cheque->id]['Тип операции'] = $cheque->type->name;
            $export[$cheque->id]['Вид оплаты'] = $cheque->payment->name;
            $export[$cheque->id]['Сумма'] = $cheque->amount;
            $export[$cheque->id]['Кол-во позиций'] = $counts[$cheque->id]['count'] ? (int)$counts[$cheque->id]['count'] : 0;
            $export[$cheque->id]['Сумма позиций'] = $counts[$cheque->id]['quantity'] ? (int)$counts[$cheque->id]['quantity'] : 0;
            $export[$cheque->id]['Дата и время'] = $cheque->created_at->format('d.m.Y H:i:s');
        }

        \Excel::create($filename, function ($excel) use ($export) {
            $excel->sheet("Отчет", function ($sheet) use ($export) {
                $sheet->fromArray($export);
            });
        })->export('xls');

        return '<script>window.close();</script>';
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
