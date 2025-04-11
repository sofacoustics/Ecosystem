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
		// if column exists, then rename it
		if (Schema::hasColumn('datasetdefs', 'bulk_upload_pattern_prefix'))
		{
			Schema::table('datasetdefs', function (Blueprint $table) {
				$table->renameColumn('bulk_upload_pattern_prefix', 'bulk_upload_filename_filter');
				$table->dropColumn('bulk_upload_pattern_id');
				$table->dropColumn('bulk_upload_pattern_suffix');
			});
		}
		// else create it
		else
		{
			Schema::table('datasetdefs', function (Blueprint $table) {
				$table->string('bulk_upload_filename_filter')->nullable();
			});
		}
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::table('datasetdefs', function (Blueprint $table) {
			$table->renameColumn('bulk_upload_filename_filter', 'bulk_upload_pattern_prefix');
			$table->string('bulk_upload_pattern_id')->nullable();
			$table->string('bulk_upload_pattern_suffix')->nullable();
		});
	}
};
