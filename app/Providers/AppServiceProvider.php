<?php

namespace App\Providers;

use App\Scopes\API\HandleScopes;
use App\Repositories\BillRepository;
use App\Scopes\API\FiltersInterface;
use Illuminate\Support\ServiceProvider;
use App\Repositories\Interfaces\BillRepositoryInterface;

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
        $this->app->bind(
            BillRepositoryInterface::class, 
            BillRepository::class,
        );

        $this->app->bind(
            FiltersInterface::class, 
            HandleScopes::class,
        );
    }
}
