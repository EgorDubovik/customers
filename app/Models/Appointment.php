<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        return $this->hasMany(AppointmentNotes::class,'appointment_id');
    }
}
