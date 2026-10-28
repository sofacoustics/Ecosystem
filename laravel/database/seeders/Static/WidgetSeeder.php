<?php

namespace Database\Seeders\Static;

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
				'service_id' => null,
				'view' => 'properties',
			],
			// 2
			[
				'key' => 'sofa-hrtf-general',
				'name' => 'SOFA: HRTF General',
				'description' => 'Plot HRTF-related figures: ETC, magnitude spectra (logarithmic and linear frequencies), ITD, and the geometry.',
				'service_id' => Service::where('key', 'octave-hrtf-general')->first()->id,
				'view' => 'hrtf-general',
			],
			// 3
			[
				'key' => 'image',
				'name' => 'Image',
				'description' => 'Display an image (PNG, JPG, WEBP, SVG, animated or static).',
				'service_id' => null,
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
				'service_id' => null,
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
				'service_id' => Service::where('key', 'octave-srir-geometry-per-m')->first()->id,
				'view' => 'srir-general',
			],
			// 8
			[
				'key' => 'sofa-directivities-polar',
				'name' => 'SOFA: Directivities Polar',
				'description' => 'Plot the directivities as polar plots.',
				'service_id' => Service::where('key', 'octave-directivities-general')->first()->id,
				'view' => 'directivity-general',
			],
			// 9
			[
				'key' => 'sofa-metadata',
				'name' => 'SOFA: Metadata',
				'description' => 'Show SOFA Properties.',
				'service_id' => Service::where('key', 'octave-sofa-properties')->first()->id,
				'view' => 'sofa-properties',
			],
			// 10
			[
				'key' => 'geometry-mesh',
				'name' => 'Geometry: Mesh',
				'description' => 'Show Geometry as a mesh.',
				'service_id' => null,
				'view' => 'mesh',
			],
			// 11
			[
				'key' => 'geometry-bezierppm',
				'name' => 'Geometry: BezierPPM',
				'description' => 'Render a mesh with the BezierPPM.',
				'service_id' => Service::where('key', 'blender-render-ppm')->first()->id,
				'view' => 'bezierppm',
			],
			// 12
			[
				'key' => 'image-spherical',
				'name' => 'Image: Spherical (JPG)',
				'description' => 'Show a 360° image (interactive).',
				'service_id' => null,
				'view' => 'image-360',
			],
			// 13
			[
				'key' => 'sofa-annotated-receiver',
				'name' => 'SOFA: AnnotatedReceiver',
				'description' => 'Plot the progress of receivers.',
				'service_id' => Service::where('key', 'octave-annotated-receiver')->first()->id,
				'view' => 'annotated-receiver',
			],
			// 14
			[
				'key' => 'sofa-headphones-general',
				'name' => 'SOFA: Headphones General',
				'description' => 'Plot spectra of headphones.',
				'service_id' => Service::where('key', 'octave-headphones')->first()->id,
				'view' => 'headphones-general',
			],
			// 15
			[
				'key' => 'sofa-selfone',
				'name' => 'SOFA: Selfone',
				'description' => 'Plot data of a headphone with multiple microphones and drivers (Selfone): energy distribution, amplitude spectra, and the geometry.',
				'service_id' => Service::where('key', 'octave-selfone')->first()->id,
				'view' => 'headphones-selfone',
			],
			// 16
			[
				'key' => 'csv-table',
				'name' => 'CSV Table',
				'description' => 'Display CSV data as a table',
				'service_id' => null,
				'view' => 'csv-table',
			],
			// 17
			[
				'key' => 'sofa-srir-general-all-m',
				'name' => 'SOFA: SRIR General (all M)',
				'description' => 'Plot the geometry, and aplitude spectra of SRIRs, all measurements.',
				'service_id' => Service::where('key', 'octave-srir-geometry-all-m')->first()->id,
				'view' => 'srir-general',
			],
		];

		foreach ($widgets as $widget) {
			// Matches by 'key', updates the rest
			Widget::updateOrCreate(['key' => $widget['key']], $widget);
		}

	}
}
