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
        Schema::create('datafiles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('location')->nullable();
            $table->unsignedBigInteger('dataset_id');
            $table->unsignedBigInteger('datasetdef_id');
            $table->foreign('dataset_id')->references('id')->on('datasets')->onDelete('cascade');
            $table->foreign('datasetdef_id')->references('id')->on('datasetdefs')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('datafiles');
    }
};
