<?php

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
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
            $table->string('title');
            $table->string('description');
            $table->enum('priority', array_column(TaskPriority::cases(), 'value'));
            $table->timestamp('due_date');
            $table->foreignId('employee_id')->constrained('users')->nullable(); //->cascadeOnDelete();
            $table->foreignId('tester_id')->constrained('users')->nullable(); //->cascadeOnDelete();
            $table->foreignId('manager_id')->constrained('users'); //->cascadeOnDelete();
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->enum('status', array_column(TaskStatus::cases(), 'value'))->default(TaskStatus::PINDING);
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