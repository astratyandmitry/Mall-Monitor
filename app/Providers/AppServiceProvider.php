<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        if (app()->environment() !== 'local') {
            \Debugbar::disable();
        }

        \View::composer('*', function ($view): void {
            if (auth()->check()) {
                $view->with('currentUser', auth()->user());
            }

            $view->with('date_types', [
                'years' => 'По годам',
                'monthes' => 'По месяцам',
                'days' => 'По дням',
                'times' => 'По часам',
            ]);

            $view->with('store_sort', [
                'top_up_count' => 'Лучшие по количеству чеков',
                'top_down_count' => 'Худшие по количеству чеков',
                'top_up_amount' => 'Лучшие суммам чека',
                'top_down_amount' => 'Худшие по суммам чека',
                'top_up_avg' => 'Лучшие по среднему чеку',
                'top_down_avg' => 'Худшие по среднему чеку',
            ]);

            $view->with('graph_date_types', [
                'daily' => 'По дням',
                'monthly' => 'По месяцам',
                'yearly' => 'По годам',
            ]);

            $view->with('monthes', [
                1 => 'Янваль',
                2 => 'Февраль',
                3 => 'Март',
                4 => 'Апрель',
                5 => 'Май',
                6 => 'Июнь',
                7 => 'Июль',
                8 => 'Август',
                9 => 'Сентябрь',
                10 => 'Октябрь',
                11 => 'Ноябрь',
                12 => 'Декабрь',
            ]);
        });
    }


    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        //
    }

}
