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
        Schema::create('task_timelines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id');
            $table->foreignId('media_id')->nullable();
            $table->foreignId('project_task_status_id');
            $table->foreignId('user_id');
            $table->tinyInteger('type');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('task_id')->references('id')->on('tasks')->restrictOnDelete();
            $table->foreign('media_id')->references('id')->on('media')->restrictOnDelete();
            $table->foreign('project_task_status_id')->references('id')->on('project_task_statuses')->restrictOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_timelines');
    }
};
