<?php

namespace App\Http\Controllers;

use App\Models\Store;

/**
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2018, ArmenianBros. <i@armenianbros.com>
 */
class ReportController extends Controller
{

    /**
     * DashboardController constructor.
     */
    public function __construct()
    {
        $this->middleware('loggined');
    }


    /**
     * @return \Illuminate\View\View
     */
    public function index(): \Illuminate\View\View
    {
        $this->setTitle('Отчет');
        $this->setActive('report');

        $statistics = \DB::table('cheques')
            ->select(\DB::raw('COUNT(*) AS count, SUM(amount) as amount, store_id'))
            ->where('created_at', '>=', '2018-04-01' . ' 00:00:00')
//            ->where('created_at', '>=', date('m') . '-01-' . date('Y') . ' 00:00:00')
            ->where('mall_id', auth()->user()->mall_id)
            ->groupBy('store_id')
            ->get();

        return view('report.index', $this->withData([
            'statistics' => $statistics,
        ]));
    }


    /**
     * @return string
     */
    public function export()
    {
        $statistics = \DB::table('cheques')
            ->select(\DB::raw('COUNT(*) AS count, SUM(amount) as amount, store_id'))
//            ->where('created_at', '>=', date('Y') . '-' . date('m') . '-01' . ' 00:00:00')
            ->where('created_at', '>=', '2018-04-01' . ' 00:00:00')
            ->where('mall_id', auth()->user()->mall_id)
            ->groupBy('store_id')
            ->get();

        $date = date('Y-m-d H:i');
        $data = [];

        foreach ($statistics as $statistic) {
            $store = Store::find($statistic->store_id);

            $data[$store->id]['Заведение'] = $store->name;
            $data[$store->id]['Количество'] = number_format($statistic->count);
            $data[$store->id]['Средний чек'] = number_format(round($statistic->amount / $statistic->count)) . ' ₸';
            $data[$store->id]['Сумма'] = number_format($statistic->amount) . ' ₸';
        }

        $dates = 'За текущий месяц';

        if (@$_GET['date_from'] && @$_GET['date_to']) {
            $dates = $this->modifyDate($_GET['date_from']) . ' — ' . $this->modifyDate($_GET['date_to']);
        } elseif (@$_GET['date_from']) {
            $dates = 'с ' . $this->modifyDate($_GET['date_from']);
        } elseif (@$_GET['date_to']) {
            $dates = 'по ' . $this->modifyDate($_GET['date_to']);
        }

        $export = [];
        foreach ($data as $place_id => $_data) {
            foreach ($_data as $key => $value) {
                $export[$place_id][$key] = $value;
            }

            $export[$place_id]['Отчетный период'] = $dates;
        }

        \Excel::create("mallmonitor_report_{$date}", function ($excel) use ($export) {
            $excel->sheet("Отчет", function ($sheet) use ($export) {
                $sheet->fromArray($export);
            });
        })->export('xls');

        return '<script>window.close();</script>';
    }


    /**
     * @param string $date
     *
     * @return string
     */
    protected function modifyDate(string $date): string
    {
        $dates = explode('-', $date);

        return "{$dates[2]}-{$dates[1]}-{$dates[0]}";
    }

}
