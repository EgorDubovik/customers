<?php

namespace App\Models\Job;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;
use App\Models\Addresses;


class Job extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'company_id',
        'status',
        'address_id',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function address()
    {
        return $this->belongsTo(Addresses::class);
    }

    public function notes(){
        return $this->hasMany(Notes::class);
    }

    public function expenses(){
        return $this->hasMany(Expense::class);
    }

    // public function totalPaid()
    // {
    //     return $this->payments->sum('amount');
    // }

    // public function totalTax()
    // {
    //     $tax = 0;
    //     foreach($this->services as $service){
    //         if($service->taxable)
    //             $tax += $service->price * (Auth::user()->settings->tax/100);
    //     }
    //     return $tax;
    // }

    // public function totalAmount()
    // {
    //     $total = 0;
    //     foreach($this->services as $service){
    //         $total += $service->price;
    //     }
    //     return $total;
    // }

    // public function remainingBalance()
    // {
    //     return round($this->totalAmount() + $this->totalTax() - $this->totalPaid(),2);
    // }
}
