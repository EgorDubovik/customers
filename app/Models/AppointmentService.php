<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
