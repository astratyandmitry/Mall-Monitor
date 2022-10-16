<?php

namespace App\Http\Controllers\Placement;

use App\DateHelper;
use App\Models\Mall;
use App\Repositories\VisitsRepository;
use Illuminate\View\View;
use App\Classes\Date\PlacementDate;
use App\Http\Controllers\Controller;
use App\Repositories\ChequeRepository;

/**
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2018, ArmenianBros. <i@armenianbros.com>
 */
class PlacementMallController extends Controller
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
        $this->setTitle('KPI ТРЦ');
        $this->setActiveSection('placement');
        $this->setActivePage('placement.mall');
        $this->addBreadcrumb('KPI', route('placement.mall.index'));

        return view('placement.mall.index', $this->withData($this->getData()));
    }

    /**
     * @return array
     */
    protected function getData(): array
    {
        PlacementDate::setupRequest();

        $periodCurrent = $this->getDataForPeriod('current');
        $periodPast = $this->getDataForPeriod('past');

        $dates = [
            'current' => DateHelper::get($periodCurrent['date_from']).' - '.DateHelper::get($periodCurrent['date_to']),
            'past' => DateHelper::get($periodPast['date_from']).' - '.DateHelper::get($periodPast['date_to']),
        ];

        $mall_ids = [];

        $statsCurrent = $this->setDataForPeriod($periodCurrent, $mall_ids);
        $statsPast = $this->setDataForPeriod($periodPast, $mall_ids);

        $mall_names = Mall::query()->whereIn('id', $mall_ids)->pluck('name', 'id');

        return [
            'statsCurrent' => $statsCurrent,
            'statsPast' => $statsPast,
            'mall_names' => $mall_names,
            'dates' => $dates,
        ];
    }

    /**
     * @param string $period
     *
     * @return null|array
     */
    protected function getDataForPeriod(string $period): ?array
    {
        $dateFrom = PlacementDate::getFromRequest($period, 'from');
        $dateTo = PlacementDate::getFromRequest($period, 'to');

        if (is_null($dateFrom) || is_null($dateTo)) {
            return ['date_from' => null, 'date_to' => null];
        }

        return [
            'stats' => ChequeRepository::getPlacementForMall($dateFrom, $dateTo),
            'visits' => VisitsRepository::getPlacementForMall($dateFrom, $dateTo),
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
        ];
    }

    /**
     * @param array|null $period
     * @param array $mall_ids
     *
     * @return array
     */
    protected function setDataForPeriod(?array $period = null, array &$mall_ids): array
    {
        if (is_null($period) || ! isset($period['stats'])) {
            return [];
        }

        $mall_ids = array_merge($mall_ids, $period['stats']->pluck('mall_id', 'mall_id')->toArray());

        $stats = $period['stats']->keyBy('mall_id')->toArray();
        $visits = $period['visits']->pluck('count', 'mall_id')->toArray();

        foreach (array_keys($stats) as $mall_id) {
            $stats[$mall_id]['visits'] = (int) @$visits[$mall_id];
        }

        return $stats;
    }
}
