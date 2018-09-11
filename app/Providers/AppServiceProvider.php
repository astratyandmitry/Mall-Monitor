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
        \View::composer('*', function ($view): void {
            if (auth()->check()) {
                $view->with('currentUser', auth()->user());
                $view->with('currentMall', auth()->user()->mall);
            }

            $view->with('dates', [
                'За последную неделю',
                'За последний месяц',
                'За последний год',
                'За все время',
                'Указать вручную',
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
