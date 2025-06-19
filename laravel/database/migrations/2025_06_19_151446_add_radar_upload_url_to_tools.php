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
			$table->string('radar_upload_url')->nullable(); // Save the RADAR upload URL here
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::table('tools', function (Blueprint $table) {
			$table->dropColumn('radar_upload_url');
		});
	}
};
