<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use App\Models\{ Task, Work_time};

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'last_name',
        'first_name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        // 'profile_photo_url',
    ];

    public function tasks()
    {
        return $this->belongsToMany(Task::class)
            ->withPivot(
                'order_num',
                'status',
                'total_work_minute',
                'total_hour',
                'total_minute',
                'completed_at')
            ->withTimestamps();
    }

    public function taskInToday()
    {
        return $this->belongsToMany(Task::class, 'task_user', 'user_id', 'task_id')
            ->joinProject()
            ->withPivot(
                'order_num',
                'status',
                'total_work_minute',
                'total_hour',
                'total_minute',
                'completed_at'
            )
            ->whereNull('task_user.deleted_at')
            ->where('status', 'today')
            ->withTimestamps();
    }

    public function taskInNow()
    {
        return $this->belongsToMany(Task::class, 'task_user', 'user_id', 'task_id')
            ->joinProject()
            ->withPivot(
                'order_num',
                'status',
                'total_work_minute',
                'total_hour',
                'total_minute',
                'completed_at'
            )
            ->whereNull('task_user.deleted_at')
            ->where('status', 'now');
    }

    public function taskInStandby()
    {
        return $this->belongsToMany(Task::class, 'task_user', 'user_id', 'task_id')
            ->joinProject()
            ->withPivot('order_num', 'status', 'total_work_minute', 'completed_at')
            ->whereNull('task_user.deleted_at')
            ->where('status', 'standby');
    }

    public function taskInDone()
    {
        return $this->belongsToMany(Task::class, 'task_user', 'user_id', 'task_id')
            ->joinProject()
            ->withPivot(
                'order_num',
                'status',
                'total_work_minute',
                'total_hour',
                'total_minute',
                'completed_at'
            )
            ->whereNull('task_user.deleted_at')
            ->where('status', 'done');
    }

    public function workTimes()
    {
        return $this->belongsToMany(Task::class, 'work_times', 'user_id', 'task_id')
            ->withPivot('executed_time', 'suspended_time', 'completed_time', 'use_date', 'deleted_at')
            ->withTimestamps();
    }
}
