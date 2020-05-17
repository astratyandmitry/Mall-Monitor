<?php

namespace App\Http\Controllers\Reports;

/**
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2019, ArmenianBros. <i@armenianbros.com>
 */
class Controller extends \App\Http\Controllers\Controller
{

    /**
     * @return int|null
     */
    protected function getExcelMaxItems(): ?int
    {
        return (app()->isLocal()) ? 50 : 5000;
    }


    /**
     * @return int
     */
    protected function getPDFMaxItems(): int
    {
        return (app()->isLocal()) ? 50 : 250;
    }

}
