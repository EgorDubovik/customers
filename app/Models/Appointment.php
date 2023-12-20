<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Appointment extends Model
{
    use HasFactory;

    public const ACTIVE = 0;
    public const DONE = 1;

    protected $fillable = [
        'customer_id',
        'start',
        'end',
        'company_id',
        'status',
        'tech_id',
        'address_id',
    ];

    public function customer(){
        return $this->belongsTo(Customer::class);
    }

    public function appointmentTechs()
    {
        return $this->hasMany(AppointmentTechs::class,'appointment_id','id');
    }

    public function services()
    {
        return $this->hasMany(AppointmentService::class,'appointment_id');
    }

    public function techs()
    {
        return $this->belongsToMany(User::class, AppointmentTechs::class, 'appointment_id','tech_id');
    }

    public function notes()
    {   
        return $this->hasMany(AppointmentNotes::class,'appointment_id')->orderBy('created_at','desc');
    }

    public function address() 
    {
        return $this->hasOne(Addresses::class,'id','address_id');
    }
    
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

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

    public function company() {
        return $this->belongsTo(Company::class);
    }
}
