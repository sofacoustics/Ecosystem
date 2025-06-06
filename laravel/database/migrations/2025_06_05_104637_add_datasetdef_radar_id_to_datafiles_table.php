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
		Schema::table('datafiles', function (Blueprint $table) {
			$table->string('datasetdef_radar_id')->nullable(); // this is the RADAR id of the folder this datafile is in on the RADAR server (datasetdef->name). Necessary for the uploadUrl endpoint
			$table->string('datasetdef_radar_upload_url')->nullable(); // this is the RADAR id of the folder this datafile is in on the RADAR server (datasetdef->name). Necessary for the uploadUrl endpoint
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('datafiles', function (Blueprint $table) {
			$table->dropColumn('datasetdef_radar_id');
			$table->dropColumn('datasetdef_radar_upload_url');
        });
    }
};
