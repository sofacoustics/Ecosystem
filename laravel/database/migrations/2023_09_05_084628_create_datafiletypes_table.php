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
        Schema::create('datafiletypes', function (Blueprint $table) {
					$table->id();
					$table->string('name');
					$table->string('description');
					$table->integer('default_widget')->nullable();
					$table->string('extension')->nullable(); 
          $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('datafiletypes');
    }
};
