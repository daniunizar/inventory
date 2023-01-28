<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\Boardgame;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
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

        //not used
        // Gate::define('update-boardgame', function (User $user, Boardgame $boardgame){
        //     return $boardgame->user_id == $user->id;
        //   });
        Gate::define('delete-boardgame', function (User $user, Boardgame $boardgame){
            return $boardgame->user_id == $user->id;
        });
    }
}
