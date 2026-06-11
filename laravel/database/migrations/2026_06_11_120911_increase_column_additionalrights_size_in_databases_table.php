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
			// increase additionalrights field from 255 -> 5000 characters
			$table->string('additionalrights', 5000)->nullable()->change();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::table('databases', function (Blueprint $table) {
			$table->string('additionalrights', 255)->nullable()->change();
		});
	}
};
