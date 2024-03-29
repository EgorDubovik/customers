<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookAppointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'key',
        'active',
    ];

    public function company(){
        return $this->belongsTo(Company::class);
    }
}
