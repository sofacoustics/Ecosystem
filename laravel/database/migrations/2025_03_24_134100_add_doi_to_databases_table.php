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
					$table->unsignedInteger('radarstatus')->nullable(); // null or 0: nothing happened with RADAR yet; 1: DOI assigned; 2: Requested publication, curator notified; 3: Database persistently published.
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
