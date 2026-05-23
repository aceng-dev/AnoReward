<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryRecommendation extends Model
{
    use HasFactory;

    protected $fillable = [
        'developer_id',
        'average_score',
        'recommendation_status',
        'proposed_bonus',
        'evaluated_at',
    ];

    protected $casts = [
        'evaluated_at' => 'date',
    ];

    
    public function developer()
    {
        return $this->belongsTo(User::class, 'developer_id');
    }
}