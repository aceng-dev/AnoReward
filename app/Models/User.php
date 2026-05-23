<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'current_salary',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    
    public function projectsAsManager()
    {
        return $this->hasMany(Project::class, 'created_by');
    }

    
    public function tasks()
    {
        return $this->hasMany(Task::class, 'developer_id');
    }

    
    public function salaryRecommendations()
    {
        return $this->hasMany(SalaryRecommendation::class, 'developer_id');
    }
}