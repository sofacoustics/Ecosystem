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
		Schema::create('tools', function (Blueprint $table) {
			$table->id();
			$table->string('filename')->nullable();
			$table->string('title');
			$table->string('additionaltitle')->nullable();
			$table->integer('additionaltitletype')->nullable();
			$table->string('descriptiongeneral', 500)->nullable();
			$table->string('descriptionabstract', 500)->nullable();
			$table->string('descriptionmethods', 500)->nullable();
			$table->string('descriptionremarks', 500)->nullable();
			$table->string('productionyear')->nullable();
			$table->string('publicationyear')->nullable();
			$table->string('language')->nullable();
			$table->integer('resourcetype')->nullable();
			$table->string('resource')->nullable();
			$table->string('datasources')->nullable();
			$table->string('software')->nullable();
			$table->string('processing')->nullable();
			$table->string('relatedinformation')->nullable();
			$table->integer('controlledrights');
			$table->string('additionalrights')->nullable();
			$table->unsignedBigInteger('user_id');
			$table->string('doi')->nullable(); // Adding DOI
			$table->unsignedInteger('radar_status')->nullable(); // Adding RADAR Status
			$table->timestamps();
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('tools');
	}
};
