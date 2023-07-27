<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Payment extends Model
{
    use HasFactory;

    public const CREDIT = 1;
    public const CASH = 2;
    public const CHECK = 3;
    public const TRANSFER = 4;

    protected $table = 'payments';
    protected $fillable = [
        'appointment_id',
        'amount',
        'payment_type',
    ];

    public function amount(): Attribute
    {
        return Attribute::make(
            get: fn($value) => number_format($value/100,2),
            set: fn($value) => round($value*100),
        );
    }
}
