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
            
            // Perbaikan: Ditambahkan ->nullable() agar sinkron dengan onDelete('set null')
            $table->foreignId('developer_id')->nullable()->constrained('users')->onDelete('set null');
            
            // Kolom ini aman: Jika project dihapus, task otomatis ikut terhapus (cascade)
           
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            
            $table->string('task_name');
            $table->enum('status', ['todo', 'inprogress', 'done', 'approved'])->default('todo');
            $table->enum('difficulty', ['Low', 'Medium', 'High'])->default('Medium');
            $table->text('description')->nullable();
            $table->date('due_date')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->integer('pm_rating')->default(0);
            $table->integer('calculated_score')->default(0);
            $table->string('repo_link')->nullable();
            $table->text('ai_review')->nullable();            
            $table->integer('ai_suggested_rating')->nullable();  
            $table->timestamps();
        });
    }

    
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};