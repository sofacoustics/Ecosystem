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
			$table->renameColumn('bulk_upload_dataset_name_prefix', 'bulk_upload_dataset_name_filter');
			$table->dropColumn('bulk_upload_dataset_name_suffix');
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::table('databases', function (Blueprint $table) {
			$table->renameColumn('bulk_upload_dataset_name_filter', 'bulk_upload_dataset_name_prefix');
            $table->string('bulk_upload_dataset_name_suffix')->nullable();
		});
	}
};
