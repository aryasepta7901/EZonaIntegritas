<?php

namespace App\Providers;

use App\Models\Rekapitulasi;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
        Gate::define('admin', function (User $user) {
            return $user->level_id === 'A';
        });
        Gate::define('pic', function (User $user) {
            return $user->level_id === 'PT';
        });
        Gate::define('EvalProv', function (User $user) {
            return $user->level_id === 'EP';
        });
        Gate::define('TPI', function (User $user) {
            return $user->level_id === 'AT' || $user->level_id === 'KT' | $user->level_id === 'DL';
        });
        Gate::define('AT', function (User $user) {
            return $user->level_id === 'AT';
        });
    }
}
