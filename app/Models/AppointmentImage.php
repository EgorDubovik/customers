<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppointmentImage extends Model
{
    use HasFactory;
    protected $table = 'appointment_images';
    protected $fillable = ['appointment_id','path','owner_id'];
}
