<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('salary_recommendations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('developer_id')->constrained('users')->onDelete('cascade');
            $table->decimal('average_score', 8, 2);
            $table->enum('recommendation_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('manager_comments')->nullable();
            $table->decimal('proposed_bonus', 8, 2)->nullable();
            $table->date('evaluated_at')->nullable();  
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_recommendations');
    }
};
