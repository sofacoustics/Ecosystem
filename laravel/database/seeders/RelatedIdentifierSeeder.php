<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\RelatedIdentifier;
use App\Models\Radar\Metadataschema;

class RelatedIdentifierSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		RelatedIdentifier::create(array(
			'relatedidentifierable_id' => 1,
			'relatedidentifierable_type' => 'App\Models\Database',
			'relatedidentifier' => 'https://doi.org/10.3758/APP.72.2.454',
			'relatedidentifiertype' => 4-1 + (\App\Models\Radar\Metadataschema::where('name', 'relatedIdentifierType')->first()->id), // DOI
			'relationtype' => 3-1 + (\App\Models\Radar\Metadataschema::where('name', 'relationType')->first()->id), // Is Supplement To
			));
	}
}
