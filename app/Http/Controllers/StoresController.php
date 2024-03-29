<?php

namespace App\Http\Controllers;

use App\DateHelper;
use App\Models\Store;
use Illuminate\View\View;
use App\Classes\Graph\GraphStorage;
use App\Repositories\StoreRepository;
use App\Repositories\ChequeRepository;
use App\Repositories\VisitsRepository;

/**
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2018, ArmenianBros. <i@armenianbros.com>
 */
class StoresController extends Controller
{
    public function __construct()
    {
        $this->middleware('not-store');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        $this->setTitle('Арендаторы');
        $this->setActiveSection('stores');
        $this->setActivePage('stores');

        $mall_id = auth()->user()->mall_id;

        $visits = VisitsRepository::getAggregatedMonthForStore($mall_id);
        $stats = ChequeRepository::getAggregatedMonthForStore($mall_id);
        $stores = StoreRepository::getListForMall($mall_id);

        return view('stores.index', $this->withData([
            'currentMonth' => mb_strtolower(DateHelper::getMonthFull()),
            'stores' => $stores,
            'visits' => $visits,
            'stats' => $stats,
        ]));
    }

    /**
     * @param \App\Models\Store $store
     *
     * @return \Illuminate\View\View
     */
    public function show(Store $store): View
    {
        $user = auth()->user();

        abort_if($user->mall_id && $user->mall_id != $store->mall_id, 404);

        $this->setTitle($store->name);
        $this->setActiveSection('stores');
        $this->setActivePage('stores');
        $this->addBreadcrumb('Арендаторы', route('stores.index'));

        $graphVisits = new GraphStorage;
        $visits = VisitsRepository::getAggregatedForStore($store->id);
        $visits->map(function ($visit) use ($graphVisits) {
            $graphVisits
                ->addValueLabel($visit->date)
                ->addValueCount($visit->count);
        });

        $graphStats = new GraphStorage;
        $stats = ChequeRepository::getAggregatedForStore($store->id);
        $stats->map(function ($stat) use ($graphStats) {
            $graphStats
                ->addValueLabel($stat['date'])
                ->addValueAmount($stat['amount'])
                ->addValueCount($stat['count'])
                ->addValueAvg($stat['avg']);
        });

        $data1 = $graphStats->getReverseData();
        $data2 = $graphVisits->getReverseData();
        $series = [];

        if (isset($data1['labels']) && count($data1['labels'])) {
            for ($i = 0; $i < count($data1['labels']); $i++) {
                $series['amount'][$i] = [
                    'name' => $data1['labels'][$i],
                    'y' => isset($data1['amount'][$i]) ? $data1['amount'][$i] : 0,
                ];

                $series['count'][$i] = [
                    'name' => $data1['labels'][$i],
                    'y' => isset($data1['count'][$i]) ? $data1['count'][$i] : 0,
                ];

                $series['avg'][$i] = [
                    'name' => $data1['labels'][$i],
                    'y' => isset($data1['avg'][$i]) ? $data1['avg'][$i] : 0,
                ];
            }
        }

        if (isset($data2['labels']) && count($data2['labels'])) {
            for ($i = 0; $i < count($data2['labels']); $i++) {
                $series['visits'][$i] = [
                    'name' => $data2['labels'][$i],
                    'y' => isset($data2['count'][$i]) ? $data2['count'][$i] : 0,
                ];
            }
        }

        return view('stores.show', $this->withData([
            'visits' => $visits->pluck('count', 'date')->toArray(),
            'graphVisits' => $graphVisits->getReverseData(),
            'graphStats' => $graphStats->getReverseData(),
            'series' => $series,
            'stats' => $stats,
            'store' => $store,
        ]));
    }
}
