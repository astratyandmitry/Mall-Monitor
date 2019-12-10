<?php

namespace App\Classes;

/**
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2019, ArmenianBros. <i@armenianbros.com>
 */
class ReportDate
{

    /**
     * @param string $key
     *
     * @return string|null
     */
    public static function getFromRequest(string $key): ?string
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
