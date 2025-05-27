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
        Schema::table('databases', function (Blueprint $table) {
					$table->boolean('visible')->nullable(); // Adding flag for the database being visible in the Ecosystem to others
					$table->string('doi')->nullable(); // Adding DOI
					// Adding RADAR Status null oder
					// 0: nichts im Bezug auf RADAR gemacht
					// 1: DOI assigned.
					// 2: Database persistently published.
					$table->unsignedInteger('radarstatus')->nullable(); 
		});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('databases', function (Blueprint $table) {
					$table->dropColumn('visible'); // Dropping the added column
					$table->dropColumn('doi'); // Dropping the added column
					$table->dropColumn('radarstatus'); // Dropping the added column
        });
    }
};
