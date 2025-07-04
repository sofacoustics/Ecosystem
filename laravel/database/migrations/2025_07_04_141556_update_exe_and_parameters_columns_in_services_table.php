<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
		DB::table('services')->where('id', 7)->update(['exe' => '/var/www/.local/bin/uv']);
		DB::table('services')->where('id', 7)->update(['parameters' => 'run CSVppm.py --input']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
