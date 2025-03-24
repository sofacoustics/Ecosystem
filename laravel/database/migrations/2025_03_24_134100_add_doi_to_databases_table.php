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
					$table->boolean('published')->nullable(); // Adding Published
					$table->string('doi')->nullable(); // Adding DOI
					$table->unsignedInteger('radarstatus')->nullable(); // Adding RADAR Status
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('databases', function (Blueprint $table) {
					$table->dropColumn('published'); // Dropping the added column
					$table->dropColumn('doi'); // Dropping the added column
					$table->dropColumn('radarstatus'); // Dropping the added column
        });
    }
};
