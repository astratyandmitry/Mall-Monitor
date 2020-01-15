<?php

namespace App\Http\Controllers\Placement;

use App\DateHelper;
use App\Models\Mall;
use App\Models\Store;
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
class PlacementStoreController extends Controller
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
        $this->setTitle('Положение арендаторов');
        $this->setActiveSection('placement');
        $this->setActivePage('placement.store');
        $this->addBreadcrumb('Положение', route('placement.store.index'));

        return view('placement.store.index', $this->withData($this->getData()));
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
            'current' => DateHelper::get($periodCurrent['date_from']) . ' - ' . DateHelper::get($periodCurrent['date_to']),
            'past' => DateHelper::get($periodPast['date_from']) . ' - ' . DateHelper::get($periodPast['date_to']),
        ];

        $mall_ids = [];
        $store_ids = [];

        $statsCurrent = $this->setDataForPeriod($periodCurrent, $mall_ids, $store_ids);
        $statsPast = $this->setDataForPeriod($periodPast, $mall_ids, $store_ids);

        $mall_names = Mall::query()->whereIn('id', $mall_ids)->pluck('name', 'id')->toArray();
        $store_names = Store::query()->select(\DB::raw('id,name,business_identification_number,mall_id'))
            ->whereIn('id', $store_ids)->get()->keyBy('id')->toArray();

        return [
            'statsCurrent' => $statsCurrent,
            'statsPast' => $statsPast,
            'store_names' => $store_names,
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
            return null;
        }

        return [
            'stats' => ChequeRepository::getPlacementForStore($dateFrom, $dateTo),
            'visits' => VisitsRepository::getPlacementForStore($dateFrom, $dateTo),
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
        ];
    }


    /**
     * @param array|null $period
     * @param array      $mall_ids
     * @param array      $store_ids
     *
     * @return array
     */
    protected function setDataForPeriod(?array $period = null, array &$mall_ids, array &$store_ids): array
    {
        if (is_null($period)) {
            return [];
        }

        $mall_ids = array_merge($mall_ids, $period['stats']->pluck('mall_id', 'mall_id')->toArray());
        $store_ids = array_merge($store_ids, $period['stats']->pluck('store_id', 'store_id')->toArray());

        $stats = $period['stats']->keyBy('store_id')->toArray();
        $visits = $period['visits']->pluck('count', 'store_id')->toArray();

        foreach (array_keys($stats) as $store_id) {
            $stats[$store_id]['visits'] = (int)@$visits[$store_id];
        }

        return $stats;
    }

}
