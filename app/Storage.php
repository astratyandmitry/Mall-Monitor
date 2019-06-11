<?php

namespace App;

/**
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2019, ArmenianBros. <i@armenianbros.com>
 */
class Storage
{

    /**
     * @var array
     */
    public static $filterCurrentTypes = [
        'days-7' => 'Крайние 7 дней',
        'week-full' => 'Полная крайняя неделя',
        'week' => 'Неделя',
        'month-full' => 'Полный крайний месяц',
        'days-30' => 'Крайние 30 дней',
    ];

    /**
     * @var array
     */
    public static $filterPastTypes = [
        'year' => 'За период прошлого года',
    ];

}
