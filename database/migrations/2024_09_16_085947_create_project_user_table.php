<?php

use App\Enums\EmployeeRole;
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
        Schema::create('project_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users'); //->cascadeOnDelete();
            $table->foreignId('project_id')->constrained('projects'); //->cascadeOnDelete();
            $table->enum('role', [array_column(EmployeeRole::cases(), 'value')]);
            $table->integer('contribution_hours')->nullable();
            $table->timestamp('last_activity')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_user');
    }
};