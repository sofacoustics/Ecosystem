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
        Schema::create('metadataschemas', function (Blueprint $table) {
            $table->id();
            $table->string('version');
            $table->string('type');
            $table->string('name');
            $table->string('value');
            $table->string('display'); // the value to display, if different from 'value'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('metadataschemas');
    }
};
