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
                'По датам',
                'По месяцам',
                'По годам',
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
