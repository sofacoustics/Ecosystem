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
 		// We're going to add a 'key' value for each of our existing datafiletypes.
		// This assumes that the primary keys are correct. Please check before running!
		// Once this is done, all further seeding operations can use the 'key' rather
		// than a hard-coded primary key.
		$updates = [
			1 => 'spatial-acoustics-hrtfs-sofa',
			2 => 'spatial-acoustics-brirs-sofa',
			3 => 'spatial-acoustics-srirs-sofa',
			4 => 'spatial-acoustics-directivities-sofa',
			5 => 'spatial-acoustics-general-sofa',
			6 => 'human-and-alike-geometry-non-parametric',
			7 => 'human-and-alike-geometry-parametric-csv',
			8 => 'human-and-alike-image',
			9 => 'human-and-human-set-of-photos-animated-webp',
			10 => 'environment-spherical-image',
			11 => 'environment-cad-models-any',
			12 => 'multisensory-explicit-spatial-data-sofa',
			13 => 'multisensory-implicit-spatial-data-audio',
			14 => 'non-spatial-acoustic-data-sofa',
			15 => 'non-spatial-audio',
			16 => 'non-spatial-table-csv',
			17 => 'other-any-type',
			18 => 'neural-network-pytorch',
		];

		foreach($updates as $id => $key) {
			DB::table('datafiletypes')
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
		$ids = [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18];
		DB::table('datafiletypes')
			->whereIn('id', $ids)
			->update(['key' => null]);
    }
};
