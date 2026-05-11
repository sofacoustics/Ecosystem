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
		// We're going to add a 'key' value for each of our existing widgets.
		// This assumes that the primary keys are correct. Please check before running!
		// Once this is done, all further seeding operations can use the 'key' rather
		// than a hard-coded primary key.
		$updates = [
			1 => 'properties',
			2 => 'sofa-hrtf-general',
			3 => 'image',
			4 => 'sofa-brir-listenerview',
			5 => 'audio',
			6 => 'sofa-brir-general',
			7 => 'sofa-srir-general',
			8 => 'sofa-directivities-polar',
			9 => 'sofa-metadata',
			10 => 'geometry-mesh',
			11 => 'geometry-bezierppm',
			12 => 'image-spherical',
			13 => 'sofa-annotated-receiver',
			14 => 'sofa-headphones-general',
			15 => 'sofa-selfone',
		];

		foreach($updates as $id => $key) {
			DB::table('widgets')
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
		$ids = [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15];
		DB::table('widgets')
			->whereIn('id', $ids)
			->update(['key' => null]);
    }
};
