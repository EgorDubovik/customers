<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Addresses extends Model
{
    use HasFactory;
    protected $table = 'addresses';
    protected $fillable = [
        'line1',
        'line2',
        'city',
        'state',
        'zip'
    ];

    public function full(){
        return $this->line1." ".$this->line2.", ".$this->city." ".$this->state.", ".$this->zip;
    }

}
