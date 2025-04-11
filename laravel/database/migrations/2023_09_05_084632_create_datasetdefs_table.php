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
        Schema::create('datasetdefs', function (Blueprint $table) {
          $table->id();
          $table->string('name', 100);
          $table->unsignedBigInteger('database_id');
          $table->unsignedBigInteger('datafiletype_id');
          $table->string('bulk_upload_pattern_prefix')->nullable();
          $table->string('bulk_upload_pattern_id')->nullable();
          $table->string('bulk_upload_pattern_suffix')->nullable();
          $table->foreignId('widget_id')->nullable()->constrained();
          $table->foreign('database_id')->references('id')->on('databases')->onDelete('cascade');
          $table->foreign('datafiletype_id')->references('id')->on('datafiletypes')->onDelete('cascade');
          $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('datasetdefs');
    }
};
