<?php

namespace App\Http\Controllers\Compare;

use App\Models\Mall;
use App\Repositories\VisitsRepository;
use Illuminate\View\View;
use App\Classes\Graph\GraphStorage;
use App\Repositories\ChequeRepository;

/**
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2018, ArmenianBros. <i@armenianbros.com>
 */
class CompareMallController extends Controller
{

    /**
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        $this->setTitle('Сравнение ТРЦ');
        $this->setActiveSection('compare');
        $this->setActivePage('compare.mall');
        $this->addBreadcrumb('Сравнение', route('placement.mall.index'));

        $graph = new GraphStorage;
        $mall_names = Mall::query()->pluck('name', 'id')->toArray();

        $visits = VisitsRepository::getCompareForMall();
        $visitsSimplified = [];

        foreach ($visits as $date => $data) {
            foreach ($data as $item) {
                if ( ! isset($mall_names[$item->mall_id])) {
                    continue;
                }

                $visitsSimplified[$date][$item->mall_id] = $item->count;
            }
        }

        $stats = ChequeRepository::getCompareForMall();

        foreach ($stats as $date => $data) {
            $graph->addMultiLabel($date, $date);

            foreach ($data as $item) {
                if ( ! isset($mall_names[$item->mall_id])) {
                    continue;
                }

                $graph
                    ->addMultiValue(GraphStorage::AMOUNT, $item->mall_id, $date, $item->amount)
                    ->addMultiValue(GraphStorage::COUNT, $item->mall_id, $date, $item->count)
                    ->addMultiValue(GraphStorage::AVG, $item->mall_id, $date, $item->avg)
                    ->addMultiValue(GraphStorage::VISITS, $item->mall_id, $date, (int)@$visitsSimplified[$date][$item->mall_id]);
            }
        }

        return view('compare.mall.index', $this->withData([
            'statsExists' => (bool)count($stats),
            'mall_names' => $mall_names,
            'graph' => $graph->getData(),
        ]));
    }

}
