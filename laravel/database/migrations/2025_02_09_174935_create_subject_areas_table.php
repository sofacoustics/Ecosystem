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
        Schema::create('subject_areas', function (Blueprint $table) {
            $table->id();
						$table->unsignedBigInteger('subjectareaable_id');
						$table->string('subjectareaable_type');
						$table->unsignedInteger('controlledSubjectAreaIndex');
						$table->string('additionalSubjectArea')->nullable();
						$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subject_areas');
    }
};
