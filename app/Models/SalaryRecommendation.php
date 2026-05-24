<?php

namespace App\Models;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryRecommendation extends Model
{
    use HasFactory;

    protected $fillable = [
        'developer_id',
        'average_score',
        'recommendation_status',
        'manager_comments',
        'proposed_bonus',
        'evaluated_at',
    ];

    protected $casts = [
        'evaluated_at' => 'datetime',
    ];

    public function getStatusAttribute()
    {
        return $this->recommendation_status;
    }

    public function setStatusAttribute($value)
    {
        $this->attributes['recommendation_status'] = $value;
    }

    public function getAmountAttribute()
    {
        return $this->proposed_bonus;
    }

    public function setAmountAttribute($value)
    {
        $this->attributes['proposed_bonus'] = $value;
    }

    public function getReasonAttribute()
    {
        return $this->manager_comments;
    }

    public function setReasonAttribute($value)
    {
        $this->attributes['manager_comments'] = $value;
    }

    public function getPeriodMonthAttribute()
    {
        return $this->evaluated_at ?? $this->created_at;
    }

    public function getApprovedAtAttribute()
    {
        return $this->evaluated_at;
    }

    public function getRejectionReasonAttribute()
    {
        return $this->manager_comments;
    }

    public function getRejectedAtAttribute()
    {
        return $this->evaluated_at;
    }

    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id');
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function developer()
    {
        return $this->belongsTo(User::class, 'developer_id');
    }
}