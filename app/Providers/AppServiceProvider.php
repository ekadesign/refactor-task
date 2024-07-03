<?php

namespace App\Providers;

use App\Repositories\LoyaltyAccountRepository;
use App\Repositories\LoyaltyAccountRepositoryInterface;
use App\Repositories\LoyaltyPointsTransactionRepository;
use App\Repositories\LoyaltyPointsTransactionRepositoryInterface;
use App\Services\LoyaltyPointsServiceInterface;
use App\Services\LoyaltyPointsService;
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
        $this->app->bind(LoyaltyAccountRepositoryInterface::class, LoyaltyAccountRepository::class);
        $this->app->bind(LoyaltyPointsTransactionRepositoryInterface::class, LoyaltyPointsTransactionRepository::class);
        $this->app->bind(LoyaltyPointsServiceInterface::class, LoyaltyPointsService::class);
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
