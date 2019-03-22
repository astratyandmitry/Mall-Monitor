<?php

namespace App\Http\Controllers;

/**
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2019, ArmenianBros. <i@armenianbros.com>
 */
class HomeController extends Controller
{

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirect(): \Illuminate\Http\RedirectResponse
    {
        return redirect()->route('dashboard');
    }

}
