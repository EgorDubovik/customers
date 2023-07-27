<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class AppointmentService extends Model
{
    use HasFactory;

    protected $table = 'appointment_services';
    protected $fillable = [
        'title',
        'price',
        'description',
        'appointment_id',
    ];

    public function appointment() {
        return $this->belongsTo(Appointment::class, 'appointment_id');
    }

    public function price(): Attribute
    {
        return Attribute::make(
            get: fn($value) => number_format($value/100,2),
            set: fn($value) => round($value*100),
        );
    }
}
