<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $dates = ['due_date'];
    protected $fillable = [
        'title',
        'description',
        'priority',
        'due_date',
        'employee_id',
        'manager_id',
        'project_id',
    ];
    protected $guarded = [
        'status',
    ];
    protected $casts = [
        'due_date'   =>  "datetime:Y-m-d H:i",
    ];
    protected $attributes = [
        'status' => 'pinding'
    ];
    protected $perPage = 10;

    public function project()
    {
        return $this->belongTo(Project::class);
    }
    public function notes()
    {
        return $this->hasMany(Note::class);
    }
}