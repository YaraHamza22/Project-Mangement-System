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
        Schema::create('tasks', function (Blueprint $table) {
         $table->id();
    $table->string('name');
    $table->text('description');
   $table->enum('status', ['pending', 'in_progress', 'completed'])->default('pending');
     $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
     $table->date('due_date');
    $table->foreignId('project_id')->constrained()->onDelete('cascade');
    $table->foreignId('assigned_to_user_id')->constrained('users')->onDelete('cascade');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
