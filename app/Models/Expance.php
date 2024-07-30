<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expance extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'amount',
        'appointment_id',
        'user_id',
        'company_id',
    ];
}
