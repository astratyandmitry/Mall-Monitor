<?php

namespace App\Http\Controllers\Compare;

use App\Models\Mall;
use App\Models\Cheque;
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
       $this->setupDates();

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
