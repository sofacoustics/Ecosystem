<?php

namespace Database\Seeders;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Service;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
     public function up(): void
    {
        $seeder = new ServiceSeeder_2025_12_20_223347();
        $seeder->run();
    }
		
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {     
    }
};
