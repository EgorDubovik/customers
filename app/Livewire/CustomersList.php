<?php

namespace App\Livewire;

use App\Models\Customer;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class CustomersList extends Component
{

    public $search = "";
    public $customers;
    
    public function render()
    {
        $this->customers = Customer::search($this->search)
            ->orderBy('updated_at','DESC')
            ->limit(50)
            ->get();

        return view('livewire.customers-list',['customers' => $this->customers]);
    }
}
