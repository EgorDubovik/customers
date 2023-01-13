<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $table = 'customer';
    protected $fillable = [
        'name',
        'phone',
        'email',
        'address_id',
        'company_id',
    ];

    public function address(){
        return $this->hasOne(Addresses::class,'id','address_id');
    }

    public function tags(){
        return $this->belongsToMany(Tag::class,'customer_tags');
    }

}
