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
      Service::create(array(
                    'name' => 'Octave: HRTF General',
                    'description' => 'Plot HRTF-related figures: Time domain: ETC left/right; Frequency domain (lin): magnitude spectrum left/right; Frequency domain (log): magnitude spectrum left/right logarithmic; ITD; Geometry.',
                    'exe' => 'sudo -u sonicom /opt/scripts/run-octave-gui.sh',
										// at dev server: 'sudo -u sonicom /home/sonicom/isf-sonicom-laravel/octave/run-octave-gui.sh'
                    'parameters' => 'HRTFGeneral.m'
                )
            );
 			Service::create(array(
                    'name' => 'Octave: BRIR ListenerView',
                    'description' => 'Plot BRIR with ListenerView as parameter.',
                    'exe' => 'XDG_CACHE_HOME=/run/user/33/sonicom-xdg-cache-home XDG_RUNTIME_DIR=/run/user/33 xvfb-run -a octave-cli',
                    'parameters' => 'BRIRListenerView.m'
                )
            );
			Service::create(array(
                    'name' => 'Octave: BRIR General',
                    'description' => 'Plot BRIR General.',
                    'exe' => 'XDG_CACHE_HOME=/run/user/33/sonicom-xdg-cache-home XDG_RUNTIME_DIR=/run/user/33 xvfb-run -a octave-cli',
                    'parameters' => 'BRIRGeneral.m'
                )
            );
			Service::create(array(
                   'name' => 'Octave: SRIR Geometry per M',
                   'description' => 'For each M, plots the geometry of the measurement from four perspectives.',
                    'exe' => 'sudo -u sonicom /opt/scripts/run-octave-gui.sh',
                   'parameters' => 'SRIRGeneral.m'
               )
            );
			Service::create(array(
                   'name' => 'Octave: Directivities General',
                   'description' => 'Plot the geometry and directivity as polar plots per frequency.',
                   'exe' => 'XDG_CACHE_HOME=/run/user/33/sonicom-xdg-cache-home XDG_RUNTIME_DIR=/run/user/33 xvfb-run -a octave-cli',
                   'parameters' => 'DirectivityGeneral.m'
               )
            );
			Service::create(array(
                   'name' => 'Octave: SOFA Properties',
                   'description' => 'Show SOFA Properties.',
                   'exe' => 'octave-cli',
                   'parameters' => 'SofaProperties.m'
               )
            );
			Service::create(array(
                   'name' => 'Blender: Render PPM',
                   'description' => 'Render PPM if BezierPPM, show CSV file properties otherwise.',
                   'exe' => '/var/www/.local/bin/uv',
                   'parameters' => 'run CSVppm.py --input'
               )
            );
			Service::create(array(
                   'name' => 'Octave: AnnotatedReceiver',
                   'description' => 'Plot the progress of receivers.',
                   'exe' => 'octave-cli',
                   'parameters' => 'AnnotatedReceiver.m'
               )
            );
			Service::create(array(
                   'name' => 'Octave: Headphones',
                   'description' => 'Plot spectra of headphones.',
                   'exe' => 'octave-cli',
                   'parameters' => 'Headphones.m'
               )
            );
    }
}
