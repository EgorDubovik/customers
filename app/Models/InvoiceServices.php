<?php

namespace App\Models;

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
}
