<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'descripation'
    ];
    protected $guarded = [
        'status',
    ];
    protected $attributes = [
        'status' => 'waiting',
    ];
    public function users()
    {
        return $this->belongsToMany(User::class, 'project_user')
            ->withPivot(['role', 'contribution_hours', 'last_activity']);
    }


    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function oldestTask()
    {
        return $this->hasOne(Task::class)->oldestOfMany();
    }

    public function latestTask()
    {
        return $this->hasOne(Task::class)->latestOfMany();
    }

    public function notes()
    {
        return $this->hasManyThrough(Note::class, Task::class);
    }
}