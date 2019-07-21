<?php

namespace App\Http\Controllers\Placement;

use App\Models\Mall;
use App\Models\Store;
use App\Models\Cheque;

/**
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2018, ArmenianBros. <i@armenianbros.com>
 */
class PlacementStoreController extends Controller
{

    /**
     * @return \Illuminate\View\View
     */
    public function index(): \Illuminate\View\View
    {
        $this->setTitle('Положение арендаторов');
        $this->setActiveSection('placement');
        $this->setActivePage('placement.store');
        $this->addBreadcrumb('Положение', route('placement.store.index'));

        $data = $this->getExportData();

        return view('placement.store.index', $this->withData($data));
    }

    /**
     * @return string
     */
    public function exportExcel(): string
    {
        $filename = 'keruenmonitor_placement.store_' . date('YmdHi');

        \Excel::create($filename, function ($excel) {
            $excel->sheet('Положение арендаторов', function ($sheet) {
                $sheet->loadView('placement.store.export.excel', $this->getExportData());
            });
        })->export('xls');

        return '<script>window.close();</script>';
    }


    /**
     * @return mixed
     */
    public function exportPDF()
    {
        $filename = 'keruenmonitor_placement.store_' . date('YmdHi');

        $pdf = \PDF::loadView('placement.store.export.pdf', $this->getExportData($this->getPDFMaxItems()))->setPaper('a4', 'landscape');

        return $pdf->download("{$filename}.pdf");
    }


    /**
     * @param int|null $limit
     *
     * @return array
     */
    protected function getExportData(?int $limit = null): array
    {
        $this->setupDates();

        $current = $this->getDataForPeriod('current', $limit);
        $past = $this->getDataForPeriod('past', $limit);

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
            'store_names' => [],
        ];

        $mall_ids = [];
        $store_ids = [];

        $statistics_current = [];
        if ( ! is_null($current['statistics'])) {
            $mall_ids = array_merge($mall_ids, $current['statistics']->pluck('mall_id', 'mall_id')->toArray());
            $store_ids = array_merge($store_ids, $current['statistics']->pluck('store_id', 'store_id')->toArray());
            $statistics_current = $current['statistics']->keyBy('store_id')->toArray();
        }

        $statistics_past = [];
        if ( ! is_null($past['statistics'])) {
            $mall_ids = array_merge($mall_ids, $past['statistics']->pluck('mall_id', 'mall_id')->toArray());
            $store_ids = array_merge($store_ids, $past['statistics']->pluck('store_id', 'store_id')->toArray());
            $statistics_past = $past['statistics']->keyBy('store_id')->toArray();
        }

        return array_merge($data, [
            'statistics_current' => $statistics_current,
            'statistics_past' => $statistics_past,
            'mall_names' => (count($mall_ids)) ? Mall::whereIn('id', $mall_ids)->pluck('name', 'id')->toArray() : [],
            'store_names' => (count($store_ids)) ? Store::whereIn('id', $store_ids)->select([
                'id',
                'name',
                'business_identification_number',
                'mall_id',
            ])->get()->keyBy('id')->toArray() : [],
        ]);
    }


    /**
     * @param string   $period
     * @param int|null $limit
     *
     * @return null|array
     */
    protected function getDataForPeriod(string $period, ?int $limit = null): ?array
    {
        $dateFrom = $this->getDateTime($period, 'from');
        $dateTo = $this->getDateTime($period, 'to');

        if (is_null($dateFrom) || is_null($dateTo)) {
            return null;
        }

        $select = 'COUNT(*) AS count, SUM(amount) as amount, AVG(amount) as avg, mall_id, store_id';

        $statistics = Cheque::reportStore($dateFrom, $dateTo);

        if (!is_null($limit)) {
            $statistics = $statistics->limit($limit);
        }

        return [
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
            'statistics' => $statistics->select(\DB::raw($select))->groupBy('store_id')->get(),
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
