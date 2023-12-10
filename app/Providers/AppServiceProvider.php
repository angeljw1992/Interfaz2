<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\AltaEmpleado;
use App\Observers\AltaEmpleadoObserver;



class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        AltaEmpleado::observe(AltaEmpleadoObserver::class);
    }
}
