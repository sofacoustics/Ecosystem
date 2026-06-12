<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Service;

class ServiceSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		// prepare services using unique 'key', so this seeder is idempotent
		// if you want to change the 'services' table, then create a migration,
		// modify the seeder, and then run the migration and the seeder.
		$services = [
			// 1
			[
				'key' => 'octave-hrtf-general',
				'name' => 'Octave: HRTF General',
				'description' => 'Plot HRTF-related figures: Time domain: ETC left/right; Frequency domain (lin): magnitude spectrum left/right; Frequency domain (log): magnitude spectrum left/right logarithmic; ITD; Geometry.',
				'exe' => 'sudo -u sonicom /opt/scripts/run-octave-gui.sh',
				// at dev server: 'sudo -u sonicom /home/sonicom/isf-sonicom-laravel/octave/run-octave-gui.sh'
				'parameters' => 'HRTFGeneral.m',
				'timeout' => '900'
			],
			// 2
			[
				'key' => 'octave-brir-listenerview',

				'name' => 'Octave: BRIR ListenerView',
				'description' => 'Plot BRIR with ListenerView as parameter.',
				'exe' => 'XDG_CACHE_HOME=/run/user/33/sonicom-xdg-cache-home XDG_RUNTIME_DIR=/run/user/33 xvfb-run -a octave-cli',
				'parameters' => 'BRIRListenerView.m',
				'timeout' => '300'
			],
			// 3
			[
				'key' => 'octave-brir-general',
				'name' => 'Octave: BRIR General',
				'description' => 'Plot BRIR General.',
				'exe' => 'XDG_CACHE_HOME=/run/user/33/sonicom-xdg-cache-home XDG_RUNTIME_DIR=/run/user/33 xvfb-run -a octave-cli',
				'parameters' => 'BRIRGeneral.m',
				'timeout' => '300'
			],
			// 4
			[
				'key' => 'octave-srir-geometry-per-m',
				'name' => 'Octave: SRIR General',
				'description' => 'Plot SRIR General.',
				'exe' => 'sudo -u sonicom /opt/scripts/run-octave-gui.sh',
				'parameters' => 'SRIRGeneral.m',
				'timeout' => '300'
			],
			// 5
			[
				'key' => 'octave-directivities-general',
				'name' => 'Octave: Directivities General',
				'description' => 'Plot the geometry and directivity as polar plots per frequency.',
				'exe' => 'sudo -u sonicom /opt/scripts/run-octave-gui.sh',
				'parameters' => 'DirectivityGeneral.m',
				'timeout' => '300'
			],
			// 6
			[
				'key' => 'octave-sofa-properties',
				'name' => 'Octave: SOFA Properties',
				'description' => 'Show SOFA Properties.',
				'exe' => 'sudo -u sonicom /opt/scripts/run-octave-gui.sh',
				'parameters' => 'SofaProperties.m',
				'timeout' => '300'
			],
			// 7
			[
				'key' => 'blender-render-ppm',
				'name' => 'Blender: Render PPM',
				'description' => 'Render PPM if BezierPPM, show CSV file properties otherwise.',
				'exe' => '/var/www/.local/bin/uv',
				'parameters' => 'run CSVppm.py --input',
				'timeout' => '300'
			],
			// 8
			[
				'key' => 'octave-annotated-receiver',
				'name' => 'Octave: AnnotatedReceiver',
				'description' => 'Plot the progress of receivers.',
				'exe' => 'sudo -u sonicom /opt/scripts/run-octave-gui.sh',
				'parameters' => 'AnnotatedReceiver.m',
				'timeout' => '300'
			],
			// 9
			[
				'key' => 'octave-headphones',
				'name' => 'Octave: Headphones',
				'description' => 'Plot spectra of headphones.',
				'exe' => 'sudo -u sonicom /opt/scripts/run-octave-gui.sh',
				'parameters' => 'Headphones.m',
				'timeout' => '300'
			],
			// 10
			[
				'key' => 'octave-selfone',
				'name' => 'Octave: Selfone',
				'description' => 'Plot energy distribution, amplitude spectra, and the geometry of the Selfone measurements.',
				'exe' => 'sudo -u sonicom /opt/scripts/run-octave-gui.sh',
				'parameters' => 'Selfone.m',
				'timeout' => '900',
			],
		];

		foreach ($services as $service) {
			// Matches by 'key', updates the rest
			Service::updateOrCreate(['key' => $service['key']], $service);
		}

		return;
	}
}
