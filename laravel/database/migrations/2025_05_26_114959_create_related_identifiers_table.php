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
		Schema::create('related_identifiers', function (Blueprint $table) {
			$table->id();
			$table->unsignedBigInteger('relatedidentifierable_id');
			$table->string('relatedidentifierable_type');
			$table->string('name');
			$table->unsignedInteger('relatedidentifiertype');
			$table->Integer('relationtype');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('related_identifiers');
	}
};
