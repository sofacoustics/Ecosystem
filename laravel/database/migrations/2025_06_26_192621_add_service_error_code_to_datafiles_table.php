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
			$table->integer('last_service_error_code')->nullable()->default(null); // save last service error code here, so we know which datafiles need their service run again
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('datafiles', function (Blueprint $table) {
			$table->dropColumn('last_service_error_code');
            //
        });
    }
};
