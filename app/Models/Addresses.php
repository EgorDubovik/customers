<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Addresses extends Model
{
    use HasFactory;
    protected $table = 'addresses';
    protected $fillable = [
        'street',
        'city',
        'state',
        'zip'
    ];

    public function full(){
        return $this->street.", ".$this->city." ".$this->state.", ".$this->zip;
    }

}
