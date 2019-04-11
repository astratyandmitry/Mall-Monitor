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
     * @return int
     */
    protected function getPDFMaxItems(): int
    {
        return (app()->isLocal()) ? 50 : 250;
    }


    /**
     * @param string $key
     *
     * @return null|string
     */
    protected function getDateTime(string $key): ?string
    {
        if ($date = request()->query("date_{$key}")) {
            $time = request()->query("time_{$key}");

            if ( ! $time) {
                $time = ($key == 'from') ? '00:00' : '23:59';
            }

            request()->merge([
                "time_{$key}" => $time,
            ]);

            return date('Y-m-d H:i:s', strtotime("{$date} {$time}"));
        }

        return null;
    }

}
