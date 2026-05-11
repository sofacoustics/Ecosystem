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
        Schema::table('menu_items', function (Blueprint $table) {
			$updates = [
				1 => 'databases',
				2 => 'tools',
				3 => 'scenarios',
				4 => 'challenges',
				5 => 'about',
			];

			foreach($updates as $id => $key) {
				DB::table('menu_items')
					->where('id', $id)
					->update([
						'key' => $key,
						'updated_at' => now(), // DB facade doesn't auto-update timestamps
					]);
			}
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('menu_items', function (Blueprint $table) {
			$ids = [1,2,3,4,5];
			DB::table('menu_items')
				->whereIn('id', $ids)
				->update(['key' => null]);
        });
    }
};
