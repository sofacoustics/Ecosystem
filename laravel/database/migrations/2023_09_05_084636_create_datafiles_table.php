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
            $table->integer('dataset_id')->unsigned();
            $table->integer('datafiletype_id')->unsigned();
            $table->string('path');
            $table->foreign('dataset_id')->references('id')->on('datasets')->onDelete('cascade');
            $table->foreign('datafiletype_id')->references('id')->on('datafiletypes')->onDelete('cascade');
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
