<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Work_time extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'task_id',
        'executed_time',
        'suspend_time',
        'total_second',
        'hour',
        'minute',
        'use_date',
        'deleted_at',

    ];
}
