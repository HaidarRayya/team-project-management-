<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectUser extends Model
{
    use HasFactory;

    protected $table = 'project_user';
    protected $guarded = [
        'user_id',
        'project_id',
    ];
    public function project()
    {
        return $this->belongTo(Project::class);
    }
    public function user()
    {
        return $this->belongTo(User::class);
    }
}