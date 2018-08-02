<?php

namespace App\Http\Controllers;

use App\Models\Store;

/**
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2018, ArmenianBros. <i@armenianbros.com>
 */
class StoresController extends Controller
{

    /**
     * @return \Illuminate\View\View
     */
    public function index(): \Illuminate\View\View
    {
        $this->setTitle('Заведения');
        $this->setActive('stores');

        return view('stores.index', $this->withData());
    }


    /**
     * @param \App\Models\Store $store
     *
     * @return \Illuminate\View\View
     */
    public function show(Store $store): \Illuminate\View\View
    {
        $this->setTitle("{$store->name} — Заведения");
        $this->setActive('stores');

        return view('stores.show', $this->withData([
            'store' => $store,
        ]));
    }

}
