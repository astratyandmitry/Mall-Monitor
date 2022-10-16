<?php

namespace App\Http\Controllers\Compare;

use App\Models\Store;
use Illuminate\View\View;
use App\Classes\Graph\GraphStorage;
use App\Http\Controllers\Controller;
use App\Repositories\VisitsRepository;
use App\Repositories\ChequeRepository;

/**
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2018, ArmenianBros. <i@armenianbros.com>
 */
class CompareStoreController extends Controller
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
        $this->setTitle('Сравнение арендаторов');
        $this->setActiveSection('compare');
        $this->setActivePage('compare.store');
        $this->addBreadcrumb('Сравнение', route('compare.store.index'));

        $graph = new GraphStorage;
        $store_names = Store::query()->pluck('name', 'id')->toArray();

        $visits = VisitsRepository::getCompareForStore();
        $visitsSimplified = [];

        foreach ($visits as $date => $data) {
            foreach ($data as $item) {
                if (! isset($store_names[$item->store_id])) {
                    continue;
                }

                $visitsSimplified[$date][$item->store_id] = $item->count;
            }
        }

        $stats = ChequeRepository::getCompareForStore();

        foreach ($stats as $date => $data) {
            $graph->addMultiLabel($date, $date);

            foreach ($data as $item) {
                if (! isset($store_names[$item['store_id']])) {
                    continue;
                }

                $graph
                    ->addMultiValue(GraphStorage::AMOUNT, $item['store_id'], $date, $item['amount'])
                    ->addMultiValue(GraphStorage::COUNT, $item['store_id'], $date, $item['count'])
                    ->addMultiValue(GraphStorage::AVG, $item['store_id'], $date, $item['avg'])
                    ->addMultiValue(GraphStorage::VISITS, $item['store_id'], $date, (int) @$visitsSimplified[$date][$item['store_id']]);
            }
        }

        return view('compare.store.index', $this->withData([
            'statsExists' => (bool) count($stats),
            'graph' => $graph->getData(),
            'series' => $graph->getSeriesMultiData($store_names),
        ]));
    }
}
