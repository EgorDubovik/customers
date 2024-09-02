<?php

namespace App\Models\Job;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\Company;
use App\Models\Customer;
use App\Models\AppointmentTechs;
use App\Models\AppointmentService;
use App\Models\AppointmentNotes;
use App\Models\Addresses;
use App\Models\Payment;
use App\Models\AppointmentImage;
use App\Models\Expanse;


class Job extends Model
{
    use HasFactory;
    public const ACTIVE = 0;
    public const DONE = 1;

    protected $fillable = [
        'customer_id',
        'company_id',
        'status',
        'address_id',
    ];

    

    public function totalPaid()
    {
        return $this->payments->sum('amount');
    }

    public function totalTax()
    {
        $tax = 0;
        foreach($this->services as $service){
            if($service->taxable)
                $tax += $service->price * (Auth::user()->settings->tax/100);
        }
        return $tax;
    }

    public function totalAmount()
    {
        $total = 0;
        foreach($this->services as $service){
            $total += $service->price;
        }
        return $total;
    }

    public function remainingBalance()
    {
        return round($this->totalAmount() + $this->totalTax() - $this->totalPaid(),2);
    }
}
