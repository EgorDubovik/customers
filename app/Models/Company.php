<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $table = 'company';

    protected $fillable = [
        'name',
        'phone',
        'email',
        'address_id',
    ];

    public function address(){
        return $this->hasOne(Addresses::class,'id','address_id');
    }

    public function phone(): Attribute{
        return Attribute::make(
            get: fn($value) => "+1 ".preg_replace('~.*(\d{3})[^\d]{0,7}(\d{3})[^\d]{0,7}(\d{4}).*~', '($1) $2-$3', $value),
            set: fn($value) => substr(preg_replace("/[^0-9]/", "", $value),-10)
        );
    }
}
