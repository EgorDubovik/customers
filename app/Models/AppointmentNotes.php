<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppointmentNotes extends Model
{
    use HasFactory;

    protected $table = 'appointment_notes';

    protected $fillable = [
        'appointment_id',
        'creator_id',
        'text',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class,'creator_id','id');
    }


}
