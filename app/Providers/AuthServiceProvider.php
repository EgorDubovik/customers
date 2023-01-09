<?php

namespace App\Providers;

use App\Models\InventoryCategory;
use App\Models\Role;
use App\Models\User;
use App\Policies\InventoryCategoryPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
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
        InventoryCategory::class => InventoryCategoryPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // users
        Gate::define('view-users-list',function (User $user){
            return in_array(Role::ADMIN,Auth::user()->roles->pluck('role')->toArray()); // Only admin can view users list
        });

        Gate::define('update-users', function (User $user, User $upuser){
            return (in_array(Role::ADMIN,Auth::user()->roles->pluck('role')->toArray()) && Auth::user()->company_id == $upuser->company_id );
        });

        Gate::define('create-users',function (User $user){
            return in_array(Role::ADMIN,Auth::user()->roles->pluck('role')->toArray()); // Only admin can view users list
        });

        // category
        Gate::define('view-category', function (User $user, InventoryCategory $category){
            return in_array(Role::ADMIN,$user->roles->pluck('role')->toArray()) || $user->id == $category->user_id || $category->accesses->contains('user_id',$user->id) ;
        });

        // list
        Gate::define('create-inventory', function (User $user, InventoryCategory $category){
            $roles = Auth::user()->roles->pluck('role')->toArray();
            if ((in_array(Role::ADMIN, $roles) && $category->company_id == $user->company_id) || (in_array(Role::FORMAN, $roles) && $user->id == $category->user_id))
                return true;
            return false;
        });
        Gate::define('update-inventory', function (User $user, InventoryCategory $category){
            $roles = Auth::user()->roles->pluck('role')->toArray();
            if ((in_array(Role::ADMIN, $roles) && $category->company_id == $user->company_id) || (in_array(Role::FORMAN, $roles) && $user->id == $category->user_id))
                return true;
            return false;
        });

        // Acceess to category
        Gate::define('access-create-update',function (User $user, InventoryCategory $category){
            return (in_array(Role::ADMIN,$user->roles->pluck('role')->toArray()) && $user->company_id == $category->company_id );
        });

    }
}
