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
        Schema::create('radardatasetresourcetypes', function (Blueprint $table) {
            $table->id();
            $table->string('resource_type'); // as defined by RADAR. If 'OTHER', then fill out 'value'.
            $table->string('value')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('radardatasetresourcetypes');
    }
};
