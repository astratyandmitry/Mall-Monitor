<?php

namespace App\Http\Controllers\Placement;

/**
 * @version   1.0.1
 * @author    Astratyan Dmitry <astratyandmitry@gmail.com>
 * @copyright 2019, ArmenianBros. <i@armenianbros.com>
 */
class Controller extends \App\Http\Controllers\Controller
{

    /**
     * @return void
     */
    protected function setupDates(): void
    {
        $prevDates = [
            'from' => null,
            'to' => null,
        ];

        switch (\request()->get('current_type')) {
            case 'days-7':
                {
                    \request()->merge([
                        'current_date_from' => date('d.m.Y', strtotime('-7 days')),
                        'current_date_to' => date('d.m.Y', strtotime('-1 day')),
                    ]);

                    $prevDates = [
                        'from' => date('d.m.Y', strtotime('-14 days')),
                        'to' => date('d.m.Y', strtotime('-8 days')),
                    ];
                };
                break;
            case 'week-full':
                {
                    $weekKey = (date('d.m.Y', strtotime('sunday this week')) == date('d.m.Y')) ? 'this' : 'last';

                    \request()->merge([
                        'current_date_from' => date('d.m.Y', strtotime("monday {$weekKey} week")),
                        'current_date_to' => date('d.m.Y', strtotime("sunday {$weekKey} week")),
                    ]);

                    $weekKey = ($weekKey == 'last') ? '1 weeks ago' : 'last week';

                    $prevDates = [
                        'from' => date('d.m.Y', strtotime("monday {$weekKey}")),
                        'to' => date('d.m.Y', strtotime("sunday {$weekKey}")),
                    ];
                };
                break;
            case 'week':
                {
                    \request()->merge([
                        'current_date_from' => date('d.m.Y', strtotime('monday this week')),
                        'current_date_to' => date('d.m.Y', strtotime('sunday this week')),
                    ]);

                    $prevDates = [
                        'from' => date('d.m.Y', strtotime('monday last week')),
                        'to' => date('d.m.Y', strtotime('sunday last week')),
                    ];
                };
                break;
            case 'month-full':
                {
                    $year = date('Y');

                    if (date('Y.m.d', strtotime('last day this month')) == date('Y.m.d')) {
                        $month = date('m');
                    } else {
                        if ((int)date('m') == 1) {
                            $year -= 1;
                            $month = 12;
                        } else {
                            $month = (int)date('m') - 1;
                        }
                    }

                    $month = ($month < 10) ? "0{$month}" : $month;

                    \request()->merge([
                        'current_date_from' => "01.{$month}.{$year}",
                        'current_date_to' => "31.{$month}.{$year}",
                    ]);

                    if ((int)$month == 1) {
                        $year -= 1;
                        $month = 12;
                    } else {
                        $month = (int)$month - 1;
                    }

                    $month = ($month < 10) ? "0{$month}" : $month;

                    $prevDates = [
                        'from' => "01.{$month}.{$year}",
                        'to' => "31.{$month}.{$year}",
                    ];
                };
                break;
            case 'days-30':
                {
                    \request()->merge([
                        'current_date_from' => date('d.m.Y', strtotime('-30 days')),
                        'current_date_to' => date('d.m.Y', strtotime('-1 day')),
                    ]);

                    $prevDates = [
                        'from' => date('d.m.Y', strtotime('-60 days')),
                        'to' => date('d.m.Y', strtotime('-31 days')),
                    ];
                };
                break;
        };

        if (\request()->get('current_type')) {
            switch (\request()->get('past_type')) {
                case 'year':
                    {
                        \request()->merge([
                            'past_date_from' => str_replace(date('Y'), (int)date('Y') - 1, \request()->get('current_date_from')),
                            'past_date_to' => str_replace(date('Y'), (int)date('Y') - 1, \request()->get('current_date_to')),
                        ]);
                    };
                    break;
                default:
                    {
                        \request()->merge([
                            'past_date_from' => $prevDates['from'],
                            'past_date_to' => $prevDates['to'],
                        ]);
                    };
                    break;
            }
        }
    }

}
