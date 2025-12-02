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
        $seeder = new DatafiletypeSeeder2();
        $seeder->run();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
