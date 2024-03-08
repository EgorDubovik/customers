<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Auth;

class Customer extends Model
{
    use HasFactory;
    protected $table = 'customer';
    protected $fillable = [
        'name',
        'phone',
        'email',
        'company_id',
    ];


    public function phone(): Attribute{
        return Attribute::make(
            get: fn($value) => "+1 ".preg_replace('~.*(\d{3})[^\d]{0,7}(\d{3})[^\d]{0,7}(\d{4}).*~', '($1) $2-$3', $value),
            set: fn($value) => substr(preg_replace("/[^0-9]/", "", $value),-10)
        );
    }

    public function scopeSearch($query, $search=''){
        return $query->where('name', 'LIKE', "%$search%")
            ->orWhere('email', 'LIKE', "%$search%")
            ->orWhere('phone', 'LIKE', "%$search%")
            ->orWhereHas('address',function($a_query) use($search){
                $a_query->where('line1','LIKE',"%$search%");
            });
    }

    public function address(){
        return $this->hasMany(Addresses::class)->orderByDesc('created_at');
    }

    public function tags(){
        return $this->belongsToMany(Tag::class,'customer_tags');
    }

    public function notes(){
        return $this->hasMany(Note::class)->orderByDesc('created_at');
    }

    public function images(){
        return $this->hasMany(Image::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class,'customer_id','id');
    }

    public function referralStat()
    {
        return $this->hasMany(ReferalCustomerStat::class,'customer_id','id');
    }

    public function referralCode()
    {
        return $this->hasOne(ReferalLinksCode::class,'customer_id','id');
    }

}
