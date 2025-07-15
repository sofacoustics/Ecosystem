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
		// if column does not exist then add it
		if (!Schema::hasColumn('databases', 'bulk_upload_description_filter'))
		{
			Schema::table('databases', function (Blueprint $table) 
			{
				$table->string('bulk_upload_description_filter')->nullable();
			});
		}
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{ 	// if column does not exist then drop it
		if (Schema::hasColumn('databases', 'bulk_upload_description_filter'))
		{
			Schema::table('databases', function (Blueprint $table) 
			{
				$table->dropColumn('bulk_upload_description_filter');
			});
		}
	}
};
