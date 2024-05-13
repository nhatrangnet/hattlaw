<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Policies\PostPolicy;

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
        $this->app['router']->matched(function (\Illuminate\Routing\Events\RouteMatched $e) {
            $route = $e->route;
            if (!\Arr::has($route->getAction(), 'guard')) {
                return;
            }
            $routeGuard = array_get($route->getAction(), 'guard');
            $this->app['auth']->resolveUsersUsing(function ($guard = null) use ($routeGuard) {
                return $this->app['auth']->guard($routeGuard)->user();
            });
            $this->app['auth']->setDefaultDriver($routeGuard);
        });

        $this->registerPolicies();
        $this->registerAdminPolicies();
        $this->registerUserPolicies();
        $this->registerPostPolicies();

        
        
    }
    /**
     * registerAdminPolicies
     * @return true
     */
    public function registerAdminPolicies()
    {
        Gate::define('superadmin', function($admin){
            return $admin->isSuperAdmin();
        });
    }

    /**
     * registerPostPolicies
     * @return true
     */
    public function registerPostPolicies()
    {
        Gate::resource('blog_news', 'App\Policies\BlogNewsPolicy'); //view, create, update, delete blog news
    }
    public function registerUserPolicies()
    {
        Gate::resource('users', 'App\Policies\UserPolicy'); //view, create, update, delete blog news
    }
}
