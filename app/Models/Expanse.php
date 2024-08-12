<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
class Expanse extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'amount',
        'appointment_id',
        'user_id',
        'company_id',
    ];

    public function amount(): Attribute
    {
        return Attribute::make(
            get: fn($value) => round($value/100,2),
            set: fn($value) => round($value*100),
        );
    }
}
