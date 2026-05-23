<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('developer_id')->constrained('users')->onDelete('set null');
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            $table->string('task_name');
            $table->enum('status',['todo', 'inprogress','done' ,'approved'])->default('todo');
            $table->text('description')->nullable();
            $table->date('due_date')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->integer('pm_rating')->default(0);
            $table->integer('calculated_score')->default(0);
            $table->text('ai_review')->nullable();            // ini nanti jo saya rencana mo tambah Ai tapi tunggu fitur Mvp jadi
            $table->integer('ai_suggested_rating')->nullable();  // ini nanti jo saya rencana mo tambah Ai tapi tunggu fitur Mvp jadi
            $table->timestamps();
        });
    }

    
    public function down(): void
    {
        
    }
};
