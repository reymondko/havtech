<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        /**Admin Gates */
        Gate::define('admin', function ($user) {
            if($user->role == 1)
            {
                return true;
            }
            return false;
        });


        /**User Gates*/

        Gate::define('user', function ($user) {
            if($user->role != 1 && $user->enabled == 1)
            {
                return true;
            }
            return false;
        });

         /**Unapproved User Gates*/

        Gate::define('user-unapproved', function ($user) {
            if($user->role != 1 && $user->enabled == 0)
            {
                return true;
            }
            return false;
        });

        //
    }
}
