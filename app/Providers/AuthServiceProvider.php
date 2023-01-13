<?php

namespace App\Providers;

use App\Models\Customer;
use App\Models\Tag;
use App\Models\Role;
use App\Models\User;
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

        // Tag
        Gate::define('assign-tag',function (User $user,$customer_id, $tag_id){
            $customer = Customer::where('id',$customer_id)
                ->where('company_id',$user->company_id)->first();
            $tag = Tag::where('id',$tag_id)
                ->where('company_id',$user->company_id)->first();
            if ($customer && $tag)
                return true;
            return false;
        });

    }
}
