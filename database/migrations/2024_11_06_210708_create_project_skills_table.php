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
        Schema::create('project_skills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id');
            $table->foreignId('skill_id');
            $table->timestamps();

            $table->foreign('project_id')->references('id')->on('projects')->restrictOnDelete();
            $table->foreign('skill_id')->references('id')->on('skills')->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_skills');
    }
};
