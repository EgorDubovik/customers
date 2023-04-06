<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleService extends Model
{
    use HasFactory;

    protected $table = 'schedule_services';
    protected $fillable = [
        'title',
        'price',
        'description',
        'schedule_id',
    ];
}
