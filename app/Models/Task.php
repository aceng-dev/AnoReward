<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'developer_id',
        'task_name',
        'description',
        'status',
        'due_date',
        'completed_at',
        'pm_rating',
        'calculated_score',
        'ai_review',            // ini nanti jo saya rencana mo tambah Ai tapi tunggu fitur Mvp jadi
        'ai_suggested_rating',  // ini nanti jo saya rencana mo tambah Ai tapi tunggu fitur Mvp jadi
    ];

    // Cast tipe data agar otomatis menjadi objek Carbon (mudah menghitung selisih tanggal)
    protected $casts = [
        'due_date' => 'date',
        'completed_at' => 'datetime',
    ];

    // Relasi: Task ini bagian dari sebuah Project
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    // Relasi: Task ini dikerjakan oleh seorang Developer (User)
    public function developer()
    {
        return $this->belongsTo(User::class, 'developer_id');
    }
}