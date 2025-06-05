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
        Schema::table('datasets', function (Blueprint $table) {
			$table->string('radar_id')->nullable(); // set to the RADAR id for this file once uploaded successfully
			$table->string('radar_upload_url')->nullable(); // set to the RADAR id for this file once uploaded successfully
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('datasets', function (Blueprint $table) {
			$table->dropColumn('radar_id');
			$table->dropColumn('radar_upload_url');
        });
    }
};
