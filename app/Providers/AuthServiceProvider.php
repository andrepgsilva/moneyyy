<?php

namespace App\Providers;

use App\Policies\BillPolicy;
use App\Models\API\Bills\Bill;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Bill::class => BillPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes();

        Passport::tokensExpireIn(now()->addMinutes(config('jwt.ttl_token')));

        Passport::refreshTokensExpireIn(now()->addMinutes(config('jwt.ttl_refresh_token')));
    }
}
