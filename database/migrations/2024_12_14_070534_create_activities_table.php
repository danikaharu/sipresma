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
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students');
            $table->foreignId('activity_category_id')->constrained('activity_categories');
            $table->foreignId('level_id')->constrained('levels');
            $table->foreignId('award_id')->constrained('awards');
            $table->tinyInteger('award_type');
            $table->string('name');
            $table->string('place');
            $table->date('start_date');
            $table->date('end_date');
            $table->text('photo');
            $table->text('file');
            $table->text('description');
            $table->tinyInteger('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
