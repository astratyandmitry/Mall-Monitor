<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Classes\Graph\GraphStorage;
use App\Repositories\ChequeRepository;
use App\Repositories\VisitsRepository;

/**
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2018, ArmenianBros. <i@armenianbros.com>
 */
class DashboardController extends Controller
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
    public function __invoke(): View
    {
        $this->setTitle('Обзор');
        $this->setActiveSection('dashboard');
        $this->setActivePage('dashboard');

        if (auth()->user()->store_id) {
            return $this->forStore();
        }

        return $this->forMall();
    }


    /**
     * @return \Illuminate\View\View
     */
    protected function forStore(): View
    {
        $store = auth()->user()->store;

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
                ->addValueLabel($stat->date)
                ->addValueAmount($stat->amount)
                ->addValueCount($stat->count)
                ->addValueAvg($stat->avg);
        });

        return view('stores.show', $this->withData([
            'visits' => $visits->pluck('count', 'date')->toArray(),
            'graphVisits' => $graphVisits->getReverseData(),
            'graphStats' => $graphStats->getReverseData(),
            'stats' => $stats,
            'store' => $store,
        ]));
    }


    /**
     * @return \Illuminate\View\View
     */
    protected function forMall(): View
    {
        $mall_id = auth()->user()->mall_id;

        $graphVisits = new GraphStorage;
        $visits = VisitsRepository::getAggregatedForMall($mall_id);

        $visits->map(function ($visit) use ($graphVisits) {
            $graphVisits
                ->addValueLabel($visit->date)
                ->addValueCount($visit->count);
        });

        $graphStats = new GraphStorage;
        $stats = ChequeRepository::getAggregatedForMall($mall_id);
        $stats->map(function ($stat) use ($graphStats) {
            $graphStats
                ->addValueLabel($stat->date)
                ->addValueAmount($stat->amount)
                ->addValueCount($stat->count)
                ->addValueAvg($stat->avg);
        });

        return view('dashboard.index', $this->withData([
            'visits' => $visits->pluck('count', 'date')->toArray(),
            'graphVisits' => $graphVisits->getReverseData(),
            'graphStats' => $graphStats->getReverseData(),
            'stats' => $stats,
        ]));
    }

}
