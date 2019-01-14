<?php

namespace App\Http\Controllers;

use App\Models\Cheque;
use App\Models\Store;

/**
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2018, ArmenianBros. <i@armenianbros.com>
 */
class DailyReportController extends Controller
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
        $this->setTitle('Ежедневный отчет');
        $this->setActive('daily_report');

        $selected_date = \Carbon\Carbon::createFromFormat('Y-m-d', $request->query('date', date('Y-m-d')));

        $builder1 = Cheque::query()->dailyReport($selected_date);
        $builder2 = clone $builder1;

        $cheques = $builder1->paginate(250);
        $statistic = $builder2->get();

        $dates = \DB::table('cheques')
            ->select(\DB::raw('DATE(created_at) as date'))
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->pluck('date')->toArray();

        return view('daily_report.index', $this->withData([
            'selected_date' => $selected_date->format('Y-m-d'),
            'selected_store' => $request->query('store_id'),
            'statistic' => $statistic,
            'cheques' => $cheques,
            'dates' => $dates,
        ]));
    }


    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return string
     */
    public function export(\Illuminate\Http\Request $request)
    {
        $selected_date = \Carbon\Carbon::createFromFormat('Y-m-d', $request->query('date', date('Y-m-d')));
        $selected_store = $request->query('store_id');

        $filename = "mallmonitor_report_{$selected_date->format('Y-m-d')}";

        $cheques = Cheque::query()->dailyReport($selected_date)->get();

        $export = [];
        foreach ($cheques as $cheque) {
            if ( ! $selected_store) {
                $export[$cheque->id]['Заведение'] = $cheque->store->name;
            }

            $export[$cheque->id]['Код касссы'] = $cheque->kkm_code;
            $export[$cheque->id]['Номер документа'] = $cheque->number;
            $export[$cheque->id]['Сумма'] = number_format($cheque->amount) . ' ₸';
            $export[$cheque->id]['Дата и время'] = $cheque->created_at->format('d.m.Y H:i:s');
        }

        \Excel::create($filename, function ($excel) use ($export) {
            $excel->sheet("Отчет", function ($sheet) use ($export) {
                $sheet->fromArray($export);
            });
        })->export('xls');

        return '<script>window.close();</script>';
    }

}
