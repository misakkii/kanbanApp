<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'due_date',
        'color',
        'deleted_at'
    ];

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
