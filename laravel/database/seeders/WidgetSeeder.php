<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Service;
use App\Models\Widget;

class WidgetSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		// prepare services using unique 'key', so this seeder is idempotent
		// if you want to change the 'services' table, then create a migration,
		// modify the seeder, and then run the migration and the seeder.
		$widgets = [
			// 1
			[
				'key' => 'properties',
				'name' => 'Properties',
				'description' => 'Display datafile properties.',
				'view' => 'properties',
			],
			// 2
			[
				'key' => 'sofa-hrtf-general',
				'name' => 'SOFA: HRTF General',
				'description' => 'Plot HRTF-related figures: ETC, magnitude spectra (logarithmic and linear frequencies), ITD, and the geometry.',
				'service_id' => 1,
				'view' => 'hrtf-general',
			],
			// 3
			[
				'key' => 'image',
				'name' => 'Image',
				'description' => 'Display an image (PNG, JPG, WEBP, SVG, animated or static).',
				'view' => 'image',
			],
			// 4
			[
				'key' => 'sofa-brir-listenerview',
				'name' => 'SOFA: BRIR ListenerView (not implemented yet)',
				'description' => 'Plot ETC, and the geometry of the BRIRs with ListenerView as parameter.',
				'service_id' => null,
				'view' => 'brir-listenerview',
			],
			// 5
			[
				'key' => 'audio',
				'name' => 'Audio',
				'description' => 'Display an audio player for the audio file.',
				'view' => 'audio',
			],
			// 6
			[
				'key' => 'sofa-brir-general',
				'name' => 'SOFA: BRIR General (not implemented yet)',
				'description' => 'Plot ETC, and the geometry of BRIRs.',
				'service_id' => null,
				'view' => 'brir-general',
			],
			// 7
			[
				'key' => 'sofa-srir-general',
				'name' => 'SOFA: SRIR General',
				'description' => 'Plot the geometry, and aplitude spectra of SRIRs.',
				'service_id' => 4,
				'view' => 'srir-general',
			],
			// 8
			[
				'key' => 'sofa-directivities-polar',
				'name' => 'SOFA: Directivities Polar',
				'description' => 'Plot the directivities as polar plots.',
				'service_id' => 5,
				'view' => 'directivity-general',
			],
			// 9
			[
				'key' => 'sofa-metadata',
				'name' => 'SOFA: Metadata',
				'description' => 'Show SOFA Properties.',
				'service_id' => 6,
				'view' => 'sofa-properties',
			],
			// 10
			[
				'key' => 'geometry-mesh',
				'name' => 'Geometry: Mesh',
				'description' => 'Show Geometry as a mesh.',
				'view' => 'mesh',
			],
			// 11
			[
				'key' => 'geometry-bezierppm',
				'name' => 'Geometry: BezierPPM',
				'description' => 'Render a mesh with the BezierPPM.',
				'service_id' => 7,
				'view' => 'bezierppm',
			],
			// 12
			[
				'key' => 'image-spherical',
				'name' => 'Image: Spherical (JPG)',
				'description' => 'Show a 360° image (interactive).',
				'view' => 'image-360',
			],
			// 13
			[
				'key' => 'sofa-annotated-receiver',
				'name' => 'SOFA: AnnotatedReceiver',
				'description' => 'Plot the progress of receivers.',
				'service_id' => 8,
				'view' => 'annotated-receiver',
			],
			// 14
			[
				'key' => 'sofa-headphones-general',
				'name' => 'SOFA: Headphones General',
				'description' => 'Plot spectra of headphones.',
				'service_id' => 9,
				'view' => 'headphones-general',
			],
			// 15
			[
				'key' => 'sofa-selfone',
				'name' => 'SOFA: Selfone',
				'description' => 'Plot data of a headphone with multiple microphones and drivers (Selfone): energy distribution, amplitude spectra, and the geometry.',
				'view' => 'headphones-selfone',
				'service_id' => Service::where('key', 'octave-selfone')->first()->id,
			],
			// 16
			[
				'key' => 'csv-table',
				'name' => 'CSV Table',
				'description' => 'Display CSV data as a table',
				'view' => 'csv-table',
			],

		];

		foreach ($widgets as $widget) {
			// Matches by 'key', updates the rest
			Widget::updateOrCreate(['key' => $widget['key']], $widget);
		}

	}
}
