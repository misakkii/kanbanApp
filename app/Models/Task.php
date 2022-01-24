<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'title',
        'due_date',
        'created_by',
        'deleted_at'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class)
            ->withPivot('order_num', 'status', 'completed_at')
            ->withTimestamps();
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class);
    }
}
