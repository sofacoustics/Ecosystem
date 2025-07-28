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
		Schema::table('tools', function (Blueprint $table) {
			 $table->string('file_radar_id')->nullable(); // Save the uploaded file's RADAR id (e.g. iqcCQbvmGzYxYUne) here
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tools', function (Blueprint $table) {
			$table->dropColumn('file_radar_id');
        });
    }
};
