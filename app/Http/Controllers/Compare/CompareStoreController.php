<?php

namespace App\Http\Controllers\Compare;

use App\Models\Mall;
use App\Models\Cheque;
use App\Http\Controllers\Controller;
use App\Models\Store;

/**
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2018, ArmenianBros. <i@armenianbros.com>
 */
class CompareStoreController extends Controller
{

    /**
     * @return \Illuminate\View\View
     */
    public function index(): \Illuminate\View\View
    {
        $this->setTitle('Сравнение арендаторов');
        $this->setActiveSection('compare');
        $this->setActivePage('compare.store');
        $this->addBreadcrumb('Сравнение', route('compare.store.index'));

        $data = $this->getExportData();

        return view('compare.store.index', $this->withData($data));
    }


    /**
     * @return array
     */
    protected function getExportData(): array
    {
        $prevDates = [
            'from' => null,
            'to' => null,
        ];

        switch (\request()->get('current_type')) {
            case 'days-7':
                {
                    \request()->merge([
                        'current_date_from' => date('d.m.Y', strtotime('-7 days')),
                        'current_date_to' => date('d.m.Y', strtotime('-1 day')),
                    ]);

                    $prevDates = [
                        'from' => date('d.m.Y', strtotime('-14 days')),
                        'to' => date('d.m.Y', strtotime('-8 days')),
                    ];
                };
                break;
            case 'week-full':
                {
                    $weekKey = (date('d.m.Y', strtotime('sunday this week')) == date('d.m.Y')) ? 'this' : 'last';

                    \request()->merge([
                        'current_date_from' => date('d.m.Y', strtotime("monday {$weekKey} week")),
                        'current_date_to' => date('d.m.Y', strtotime("sunday {$weekKey} week")),
                    ]);

                    $weekKey = ($weekKey == 'last') ? '1 weeks ago' : 'last week';

                    $prevDates = [
                        'from' => date('d.m.Y', strtotime("monday {$weekKey}")),
                        'to' => date('d.m.Y', strtotime("sunday {$weekKey}")),
                    ];
                };
                break;
            case 'week':
                {
                    \request()->merge([
                        'current_date_from' => date('d.m.Y', strtotime('monday this week')),
                        'current_date_to' => date('d.m.Y', strtotime('sunday this week')),
                    ]);

                    $prevDates = [
                        'from' => date('d.m.Y', strtotime('monday last week')),
                        'to' => date('d.m.Y', strtotime('sunday last week')),
                    ];
                };
                break;
            case 'month-full':
                {
                    $year = date('Y');

                    if (date('Y.m.d', strtotime('last day this month')) == date('Y.m.d')) {
                        $month = date('m');
                    } else {
                        if ((int)date('m') == 1) {
                            $year -= 1;
                            $month = 12;
                        } else {
                            $month = (int)date('m') - 1;
                        }
                    }

                    $month = ($month < 10) ? "0{$month}" : $month;

                    \request()->merge([
                        'current_date_from' => "01.{$month}.{$year}",
                        'current_date_to' => "31.{$month}.{$year}",
                    ]);

                    if ((int)$month == 1) {
                        $year -= 1;
                        $month = 12;
                    } else {
                        $month = (int)$month - 1;
                    }

                    $month = ($month < 10) ? "0{$month}" : $month;

                    $prevDates = [
                        'from' => "01.{$month}.{$year}",
                        'to' => "31.{$month}.{$year}",
                    ];
                };
                break;
            case 'days-30':
                {
                    \request()->merge([
                        'current_date_from' => date('d.m.Y', strtotime('-30 days')),
                        'current_date_to' => date('d.m.Y', strtotime('-1 day')),
                    ]);

                    $prevDates = [
                        'from' => date('d.m.Y', strtotime('-60 days')),
                        'to' => date('d.m.Y', strtotime('-31 days')),
                    ];
                };
                break;
        };

        if (\request()->get('current_type')) {
            switch (\request()->get('past_type')) {
                case 'year':
                    {
                        \request()->merge([
                            'past_date_from' => str_replace(date('Y'), (int)date('Y') - 1, \request()->get('current_date_from')),
                            'past_date_to' => str_replace(date('Y'), (int)date('Y') - 1, \request()->get('current_date_to')),
                        ]);
                    };
                    break;
                default:
                    {
                        \request()->merge([
                            'past_date_from' => $prevDates['from'],
                            'past_date_to' => $prevDates['to'],
                        ]);
                    };
                    break;
            }
        }

        $statisticsCurrent = $this->getDataForPeriod('current');
        $statisticsPast = $this->getDataForPeriod('past');

        if (is_null($statisticsCurrent) || is_null($statisticsPast)) {
            return [
                'statistics_current' => [],
                'statistics_past' => [],
                'mall_names' => [],
            ];
        }

        $mall_ids = array_merge(
            $statisticsCurrent->pluck('mall_id', 'mall_id')->toArray(),
            $statisticsPast->pluck('mall_id', 'mall_id')->toArray()
        );

        $store_ids = array_merge(
            $statisticsCurrent->pluck('store_id', 'store_id')->toArray(),
            $statisticsPast->pluck('store_id', 'store_id')->toArray()
        );

        $data = [
            'statistics_current' => $statisticsCurrent->keyBy('store_id')->toArray(),
            'statistics_past' => $statisticsPast->keyBy('store_id')->toArray(),
            'mall_names' => Mall::whereIn('id', $mall_ids)->pluck('name', 'id')->toArray(),
            'store_names' => Store::whereIn('id', $store_ids)->select([
                'id',
                'name',
                'business_identification_number',
                'mall_id',
            ])->get()->keyBy('id')->toArray(),
        ];

        return $data;
    }


    /**
     * @param string $period
     *
     * @return mixed
     */
    protected function getDataForPeriod(string $period)
    {
        $dateFrom = $this->getDateTime($period, 'from');
        $dateTo = $this->getDateTime($period, 'to');

        if (is_null($dateFrom) || is_null($dateTo)) {
            return null;
        }

        $select = 'COUNT(*) AS count, SUM(amount) as amount, AVG(amount) as avg, mall_id, store_id';

        $statistics = Cheque::reportStore($dateFrom, $dateTo);

        return $statistics->select(\DB::raw($select))->groupBy('store_id')->get();
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
