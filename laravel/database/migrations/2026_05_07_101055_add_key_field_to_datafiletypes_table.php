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
        Schema::table('datafiletypes', function (Blueprint $table) {
			// add 'key' field - not *yet* unique
			$table->string('key')->nullable()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('datafiletypes', function (Blueprint $table) {
			$table->dropColumn('key');
        });
    }
};
