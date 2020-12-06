<?php

namespace App\Providers;

use App\Doctor;
use App\Observers\DoctorObserver;
use App\Observers\PatientObserver;
use App\Patient;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

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

        Doctor::observe(DoctorObserver::class);
        Patient::observe(PatientObserver::class);
        Schema::defaultStringLength(191);
    }
}
