<?php

namespace App\Http\Controllers\Placement;

use App\Models\Mall;
use App\Models\Cheque;

/**
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2018, ArmenianBros. <i@armenianbros.com>
 */
class PlacementMallController extends Controller
{

    /**
     * @return \Illuminate\View\View
     */
    public function index(): \Illuminate\View\View
    {
        $this->setTitle('Положение ТРЦ');
        $this->setActiveSection('placement');
        $this->setActivePage('placement.mall');
        $this->addBreadcrumb('Положение', route('placement.mall.index'));

        $data = $this->getExportData();

        return view('placement.mall.index', $this->withData($data));
    }


    /**
     * @return array
     */
    protected function getExportData(): array
    {
        $this->setupDates();

        $current = $this->getDataForPeriod('current');
        $past = $this->getDataForPeriod('past');

        $data = [
            'dates' => [
                'current' => [
                    'from' => date('d.m.Y H:i:s', strtotime($current['date_from'])),
                    'to' => date('d.m.Y H:i:s', strtotime($current['date_to'])),
                ],
                'past' => [
                    'from' => date('d.m.Y H:i:s', strtotime($past['date_from'])),
                    'to' => date('d.m.Y H:i:s', strtotime($past['date_to'])),
                ],
            ],
            'statistics_current' => [],
            'statistics_past' => [],
            'mall_names' => [],
        ];

        $mall_ids = [];

        $statistics_current = [];
        if ( ! is_null($current['statistics'])) {
            $mall_ids = array_merge($mall_ids, $current['statistics']->pluck('mall_id', 'mall_id')->toArray());
            $statistics_current = $current['statistics']->keyBy('mall_id')->toArray();
        }

        $statistics_past = [];
        if ( ! is_null($past['statistics'])) {
            $mall_ids = array_merge($mall_ids, $past['statistics']->pluck('mall_id', 'mall_id')->toArray());
            $statistics_past = $past['statistics']->keyBy('mall_id')->toArray();
        }

        return array_merge($data, [
            'statistics_current' => $statistics_current,
            'statistics_past' => $statistics_past,
            'mall_names' => (count($mall_ids)) ? Mall::whereIn('id', $mall_ids)->pluck('name', 'id') : [],
        ]);
    }


    /**
     * @param string $period
     *
     * @return null|array
     */
    protected function getDataForPeriod(string $period): ?array
    {
        $dateFrom = $this->getDateTime($period, 'from');
        $dateTo = $this->getDateTime($period, 'to');

        if (is_null($dateFrom) || is_null($dateTo)) {
            return null;
        }

        $select = 'COUNT(*) AS count, SUM(amount) as amount, AVG(amount) as avg, mall_id';

        $statistics = Cheque::reportMall($dateFrom, $dateTo);

        return [
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
            'statistics' => $statistics->select(\DB::raw($select))->groupBy('mall_id')->get(),
        ];
    }


    /**
     * @param string $period
     * @param string $key
     *
     * @return null|string
     */
    protected function getDateTime(string $period, string $key): ?string
    {
        if ($date = request()->query("{$period}_date_{$key}")) {
            $time = request()->query("{$period}_time_{$key}");

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
