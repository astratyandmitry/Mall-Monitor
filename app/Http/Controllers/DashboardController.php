<?php

namespace App\Http\Controllers;

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
    public function __invoke(): \Illuminate\View\View
    {
        $this->setTitle('Обзор');
        $this->setActive('dashboard');

        return view('dashboard.index', $this->withData());
    }

}
