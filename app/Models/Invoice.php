<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PDO;

class Invoice extends Model
{
    use HasFactory;

    protected $table = 'invoices';
    protected $fillable = [
        'creator_id',
        'company_id',
        'customer_id',
        'appointment_id',
        'customer_name',
        'address',
        'email',
        'status',
        'pdf_path',
        'key',
    ];

    // function services(){
    //     return $this->hasMany(InvoiceServices::class, 'invoice_id');
    // }

    function appointment(){
        return $this->belongsTo(Appointment::class);
    }

    function customer(){
        return $this->belongsTo(Customer::class);
    }

    function company(){
        return $this->belongsTo(Company::class);
    }

    public function creator(){
        return $this->belongsTo(User::class, 'creator_id');
    }
}
