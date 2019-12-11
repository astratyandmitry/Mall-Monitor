<?php

namespace App;

use App\Classes\ReportDate;

/**
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2019, ArmenianBros. <i@armenianbros.com>
 */
class DateHelper
{

    /**
     * @var array
     */
    protected static $monthFull = [
        1 => 'Январь',
        2 => 'Февраль',
        3 => 'Март',
        4 => 'Апрель',
        5 => 'Май',
        6 => 'Июнь',
        7 => 'Июль',
        8 => 'Август',
        9 => 'Сентябдь',
        10 => 'Октябрь',
        11 => 'Ноябрь',
        12 => 'Декабрь',
    ];

    /**
     * @var array
     */
    protected static $monthAbbr = [
        1 => 'янв.',
        2 => 'фев.',
        3 => 'мар.',
        4 => 'апр.',
        5 => 'май.',
        6 => 'июн.',
        7 => 'июл.',
        8 => 'авг.',
        9 => 'сен.',
        10 => 'окт.',
        11 => 'ноя.',
        12 => 'дек.',
    ];

    /**
     * @var array
     */
    protected static $dayFull = [
        1 => 'Понедельник.',
        2 => 'Вторник',
        3 => 'Среда',
        4 => 'Четверг',
        5 => 'Пятница',
        6 => 'Суббота',
        7 => 'Воскресенье',
    ];

    /**
     * @var array
     */
    protected static $dayAbbr = [
        1 => 'Пн.',
        2 => 'Вт.',
        3 => 'Ср.',
        4 => 'Чт.',
        5 => 'Пт.',
        6 => 'Сб.',
        7 => 'Вс.',
    ];


    /**
     * @param int|null $month
     *
     * @return string
     */
    public static function getMonthFull(?int $month = null): string
    {
        if (is_null($month)) {
            $month = (int)date('m');
        }

        return self::$monthFull[$month];
    }


    /**
     * @param int|null $month
     *
     * @return string
     */
    public static function getMonthAbbr(?int $month = null): string
    {
        if (is_null($month)) {
            $month = (int)date('m');
        }

        return self::$monthAbbr[$month];
    }


    /**
     * @param int|null $day
     *
     * @return string
     */
    public static function getDayFull(?int $day = null): string
    {
        if (is_null($day)) {
            $day = (int)date('N');
        }

        return self::$dayFull[$day];
    }


    /**
     * @param int|null $day
     *
     * @return string
     */
    public static function getDayAbbr(?int $day = null): string
    {
        if (is_null($day)) {
            $day = (int)date('N');
        }

        return self::$dayAbbr[$day];
    }


    /**
     * @param string $date
     *
     * @return string
     */
    public static function byDateGroup(string $date): string
    {
        switch (ReportDate::instance()->getDateGroup()) {
            case 'yearmonth':
                {
                    $dates = explode('-', $date);

                    return self::getMonthAbbr($dates[1]) . ' ' . $dates[0];
                };
                break;
            case 'date':
                {
                    return date('d.m.Y', strtotime($date));
                };
                break;
            default:
                {
                    return $date;
                };
                break;
        }
    }

}
