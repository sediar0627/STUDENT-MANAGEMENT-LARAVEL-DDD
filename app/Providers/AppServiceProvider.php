<?php

namespace App\Providers;

use App\Models\Students\Student;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Src\Api\Students\Infrastructure\Policies\StudentPolicy;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::before(function ($user, $ability) {
            return $user->hasRole('super-admin') ? true : null;
        });

        Gate::policy(Student::class, StudentPolicy::class);
    }
}
