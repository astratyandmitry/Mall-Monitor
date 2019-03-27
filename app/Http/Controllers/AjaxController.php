<?php

namespace App\Http\Controllers;

use App\Repositories\StoreRepository;
use App\Repositories\CashboxRepository;

/**
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2019, ArmenianBros. <i@armenianbros.com>
 */
class AjaxController
{

    /**
     * @return \Illuminate\View\View
     */
    public function cashboxes(): \Illuminate\View\View
    {
        $entities = CashboxRepository::getOptionsForStore(request()->get('store_id'));

        return view('ajax.options', [
            'placeholder' => 'Все',
            'entities' => $entities,
            'selected' => null,
        ]);
    }


    /**
     * @return \Illuminate\View\View
     */
    public function stores(): \Illuminate\View\View
    {
        $entities = StoreRepository::getOptions(request()->get('mall_id') ?? -1);

        return view('ajax.options', [
            'entities' => $entities,
            'selected' => null,
        ]);
    }

}
