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
            $table->foreignId('parent_task_id')->nullable();
            $table->foreignId('project_id');
            $table->foreignId('task_type_id');
            $table->foreignId('assignee_id')->nullable();
            $table->string('code');
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('due_date')->nullable();
            $table->tinyInteger('priority');
            $table->double('estimated_hours')->nullable();
            $table->double('spent_hours')->nullable();
            $table->timestamps();
            $table->foreign('parent_task_id')->references('id')->on('tasks')->restrictOnDelete();
            $table->foreign('project_id')->references('id')->on('projects')->restrictOnDelete();
            $table->foreign('task_type_id')->references('id')->on('task_types')->restrictOnDelete();
            $table->foreign('assignee_id')->references('id')->on('users')->restrictOnDelete();
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
