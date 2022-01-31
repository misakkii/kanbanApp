<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{Model, Builder};
use App\Models\{ Project, User };


class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'title',
        'due_date',
        'created_by',
        'deleted_at',
    ];

    public function scopeJoinProject($query)
    {
        return $query->join('projects', 'tasks.project_id', '=', 'projects.id')->select(
            'tasks.id',
            'tasks.project_id',
            'projects.project_name',
            'tasks.title',
            'tasks.due_date',
            'tasks.created_by',
            'tasks.completed_at',
            'tasks.deleted_at',
        );
    }

    public function users()
    {
        return $this->belongsToMany(User::class)
            ->withPivot('order_num', 'status', 'completed_at')
            ->withTimestamps();
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
