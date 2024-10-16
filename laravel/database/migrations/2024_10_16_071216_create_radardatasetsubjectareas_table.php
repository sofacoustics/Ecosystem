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
        Schema::create('radardatasetsubjectareas', function (Blueprint $table) {
            $table->id();
            $table->string('controlled_subject_area_name');
            $table->string('additional_subject_area_name')->nullable();
            $table->foreignId('radardataset_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('radardatasetsubjectareas');
    }
};
