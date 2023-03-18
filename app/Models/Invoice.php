<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $table = 'invoices';
    protected $fillable = [
        'creator_id',
        'company_id',
        'customer_id',
        'customer_name',
        'address',
        'email',
        'status',
        'pdf_path',
    ];
}
