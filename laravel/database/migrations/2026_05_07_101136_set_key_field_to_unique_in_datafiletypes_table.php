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
			// make 'key' field unique and required
			$table->string('key')->nullable(false)->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('datafiletypes', function (Blueprint $table) {
			// remove 'unique' index
			$table->dropUnique(['key']);
			// change it back to nullable and remove the uniqueness
			$table->string('key')->nullable()->change();
        });
    }
};
