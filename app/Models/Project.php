<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{ Task, };

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'project_name',
        'due_date',
        'color',
        'completed_at',
        'deleted_at',
    ];

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
