<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceServices extends Model
{
    use HasFactory;

    protected $table = 'invoice_services';

    protected $fillable = [
        'invoice_id',
        'title',
        'description',
        'price',
    ];

    public function price(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value/100,
            set: fn($value) => round($value*100),
        );
    }
}
