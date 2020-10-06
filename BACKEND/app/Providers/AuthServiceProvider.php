<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\Thread;
use App\Models\Answer;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
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

        Gate::before(function($user, $ability) {
            return $user->hasRole('Super Admins') ? true : null;
        });

        Gate::define('user-thread', function(User $user, Thread $thread) {
            return $user->id == $thread->user_id;
        });

        Gate::define('user-answer', function (User $user, Answer $answer) {
            return $user->id == $answer->user_id;
        });
    }
}
