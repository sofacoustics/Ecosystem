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
        Schema::table('services', function (Blueprint $table) {
			// add 'key' field - not *yet* unique
			$table->string('key')->nullable()->after('id');
		});
		// in the next migration we will populate the entries based on hard-coded primary keys.
		// These need to be checked beforehand so we don't break anything
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
		Schema::table('services', function (Blueprint $table) {
			$table->dropColumn('key');
        });
    }
};
