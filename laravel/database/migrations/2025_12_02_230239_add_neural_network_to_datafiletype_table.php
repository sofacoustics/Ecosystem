<?php

namespace Database\Seeders;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Datafiletype;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $seeder = new DatafiletypeSeeder_2025_12_02_230239();
        $seeder->run();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
