<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_name',
        'description',
        'status',
        'created_by',
    ];

    
    public function manager()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

  
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}