<?php

namespace App\Http\Controllers;

use App\Classes\Graph\GraphStorage;
use App\DateHelper;
use App\Models\Mall;
use Illuminate\View\View;
use App\Repositories\ChequeRepository;
use App\Repositories\VisitsRepository;

/**
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2018, ArmenianBros. <i@armenianbros.com>
 */
class MallsController extends Controller
{

    public function __construct()
    {
        $this->middleware('not-mall');
    }


    /**
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        $this->setTitle('ТРЦ');
        $this->setActiveSection('malls');
        $this->setActivePage('malls');

        $visits = VisitsRepository::getAggregatedMonthForMall();
        $stats = ChequeRepository::getAggregatedMonthForMall();

        $malls = Mall::query()->orderBy('name')->get();

        return view('malls.index', $this->withData([
            'currentMonth' => mb_strtolower(DateHelper::getMonthFull()),
            'visits' => $visits,
            'stats' => $stats,
            'malls' => $malls,
        ]));
    }


    /**
     * @param \App\Models\Mall $mall
     *
     * @return \Illuminate\View\View
     */
    public function show(Mall $mall): View
    {
        $user = auth()->user();

        abort_if($user->mall_id, 404);

        $this->setTitle($mall->name);
        $this->setActiveSection('malls');
        $this->setActivePage('malls');
        $this->addBreadcrumb('ТРЦ', route('malls.index'));

        $graphVisits = new GraphStorage;
        $visits = VisitsRepository::getAggregatedForMall($mall->id);

        $visits->map(function ($visit) use ($graphVisits) {
            $graphVisits
                ->addValueLabel($visit->date)
                ->addValueCount($visit->count);
        });

        $graphStats = new GraphStorage;
        $stats = ChequeRepository::getAggregatedForMall($mall->id);
        $stats->map(function ($stat) use ($graphStats) {
            $graphStats
                ->addValueLabel($stat->date)
                ->addValueAmount($stat->amount)
                ->addValueCount($stat->count)
                ->addValueAvg($stat->avg);
        });

        return view('malls.show', $this->withData([
            'visits' => $visits->pluck('count', 'date')->toArray(),
            'graphVisits' => $graphVisits->getReverseData(),
            'graphStats' => $graphStats->getReverseData(),
            'stats' => $stats,
            'mall' => $mall,
        ]));
    }

}
