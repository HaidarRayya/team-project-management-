<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;
    protected $fillable = [
        'descripation',
        'task_id',
        'tester_id'
    ];


    public function task()
    {
        return $this->belongTo(Task::class);
    }
}