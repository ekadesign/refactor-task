<?php

namespace App\Providers;

use App\Repositories\Interfaces\LoyaltyRepositoryInterface;
use App\Repositories\LoyaltyRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            LoyaltyRepositoryInterface::class,
            LoyaltyRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
