<?php

namespace App\Providers;

use App\Models\Company;
use App\Models\Customer;
use App\Models\Image;
use App\Models\Service;
use App\Models\Tag;
use App\Models\Role;
use App\Models\User;
use App\Models\Invoice;
use App\Models\Appointment;
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

        Gate::define('untie-tag', function (User $user, $customer_id, $tag_id){
            $customer = Customer::where('id',$customer_id)
                ->where('company_id',$user->company_id)->first();
            $tag = Tag::where('id',$tag_id)
                ->where('company_id',$user->company_id)->first();
            if ($customer && $tag)
                return true;
            return false;
        });

        Gate::define('delete-tag',function (User $user, Tag $tag){
            if ($tag->company_id == $user->company_id)
                return true;
            return false;
        });

        // Customers

        Gate::define('view-customer', function(User $user, Customer $customer){
            return $user->company_id === $customer->company_id;
        });

        Gate::define('update-customer', function (User $user, Customer $customer){
           if ($user->company_id == $customer->company_id)
               return true;
           return false;
        });

        //Notes
        Gate::define('store-note',function (User $user,Customer $customer){
            if ($user->company_id == $customer->company_id)
                return true;
            return false;
        });

        // Appointment notes
        Gate::define('appointment-store-note',function (User $user, Appointment $appointment){
            if ($user->company_id == $appointment->company_id)
                return true;
            return false;
        });

        // Appointment srvices
        Gate::define('add-remove-service-from-appointment',function(User $user, Appointment $appointment){
            if ($user->company_id == $appointment->company_id)
                return true;
            return false;
        });

        // Upload images
        Gate::define('upload-images', function (User $user, Customer $customer){
            if ($user->company_id == $customer->company_id)
                return true;
            return false;
        });
        Gate::define('delete-images', function (User $user, Image $image){
            if ($user->company_id == $image->owner->company_id)
                return true;
            return false;
        });
        Gate::define('show-images', function (User $user, Image $image){
            if ($user->company_id == $image->owner->company_id)
                return true;
            return false;
        });

        // Company
        Gate::define('edit-company',function (User $user, Company $company){
            return (in_array(Role::ADMIN,Auth::user()->roles->pluck('role')->toArray()) && Auth::user()->company_id == $company->id );
        });

        //Services
        Gate::define('update-service', function (User $user, Service $service){
            return (in_array(Role::ADMIN,Auth::user()->roles->pluck('role')->toArray()) && Auth::user()->company_id == $service->company_id );
        });

        // Invoice
        Gate::define('can-view-invoice', function(User $user, Invoice $invoice){
            return $user->company_id === $invoice->company_id;
        });
        
        Gate::define('can-send-by-customer', function(User $user, Customer $customer){
            return $user->company_id === $customer->company_id;
        });
    }
}
