<?php

namespace App\Livewire\Customer;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Models\Customer;

class CreateCustomer extends Component
{
    public $phone;
    public $address;
    public $customers = [];

    public function render()
    {

        if(!empty($this->phone) || !empty($this->address))
            $this->customers = Customer::where('company_id', Auth::user()->company_id)
                ->where(function($query){
                    $query->orWhere('phone','LIKE',"%$this->phone%");
                })->get();

        return view('livewire.customer.create-customer');
    }
}
