<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Radardataset;

return new class extends Migration
{
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('databases', function (Blueprint $table) {
			$table->id();
			// creators --> Table creators
			$table->string('title');
			$table->integer('additionaltitletype')->nullable();
			$table->string('additionaltitle')->nullable();
			$table->string('descriptiongeneral', 500)->nullable();
			$table->string('descriptionabstract', 500)->nullable();
			$table->string('descriptionmethod', 500)->nullable();
			$table->string('descriptionremarks', 500)->nullable();
			// keywords --> Table keywords
			// publishers --> Table publishers
			$table->string('productionyear')->nullable();
			$table->string('publicationyear')->nullable();
			$table->string('language')->nullable();
			// subjectAreas --> Table subjectareas
			$table->integer('resourcetype')->nullable();
			$table->string('resource')->nullable();
			// geolocations --> Table geolocations
			$table->string('datasources')->nullable();
			$table->string('software')->nullable();
			$table->string('processing')->nullable();
			// rightsholders --> Table rightholders
			$table->string('relatedinformation')->nullable();
			$table->integer('controlledrights');
			$table->string('additionalrights')->nullable();
			// fundingreferences --> Table fundingreferences
			$table->string('bulk_upload_dataset_name_prefix')->nullable();
			$table->string('bulk_upload_dataset_name_suffix')->nullable();
			$table->json('radardataset')->nullable();
			$table->unsignedBigInteger('user_id');
			$table->string('radar_id')->nullable(); // Save the RADAR id (e.g. iqcCQbvmGzYxYUne) here
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('databases');
	}
};
