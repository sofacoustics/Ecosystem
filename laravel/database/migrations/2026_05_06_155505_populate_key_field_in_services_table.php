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
		// We're going to add a 'key' value for each of our existing services.
		// This assumes that the primary keys are correct. Please check before running!
		// Once this is done, all further seeding operations can use the 'key' rather
		// than a hard-coded primary key.
		$updates = [
			1 => 'octave-hrtf-general',
			2 => 'octave-brir-listenerview',
			3 => 'octave-brir-general',
			4 => 'octave-srir-geometry-per-m',
			5 => 'octave-directivities-general',
			6 => 'octave-sofa-properties',
			7 => 'blender-render-ppm',
			8 => 'octave-annotated-receiver',
			9 => 'octave-headphones',
			10 => 'octave-selfone',
		];
	
		foreach($updates as $id => $key) {
			DB::table('services')
	            ->where('id', $id)
	            ->update([
					'key' => $key,
					'updated_at' => now(), // DB facade doesn't auto-update timestamps
				]);
		}
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
	{
		$ids = [1,2,3,4,5,6,7,8,9,10];
		DB::table('services')
	            ->whereIn('id', $ids)
	            ->update(['key' => null]);
    }
};
