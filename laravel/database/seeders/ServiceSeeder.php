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
                    'description' => 'Plot hrtf-related figures: time domain: ETC left/right; frequency domain (lin): magnitude spectrum left/right; frequency domain (log): magnitude spectrum left/right logarithmic; ITD; Geometry.',
                    'exe' => 'XDG_CACHE_HOME=/run/user/33/sonicom-xdg-cache-home XDG_RUNTIME_DIR=/run/user/33 xvfb-run -a octave-cli',
                    'parameters' => 'HRTFGeneral.m'
                )
            );
            Service::create(array(
                    'name' => 'Octave: ETC horizontal plane',
                    'description' => 'Plot ETC in the horizontal plane.',
                    'exe' => 'octave-cli',
                    'parameters' => 'HRIR1.m'
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
                   'name' => 'Octave: SRIR Geometry',
                   'description' => 'Plot SRIR Geometry.',
                    'exe' => 'XDG_CACHE_HOME=/run/user/33/sonicom-xdg-cache-home XDG_RUNTIME_DIR=/run/user/33 xvfb-run -a octave-cli',
                   'parameters' => 'SRIRGeometry.m'
               )
            );
			Service::create(array(
                   'name' => 'Octave: Directivities General',
                   'description' => 'Plot the directivities as polar plots, and as filled contour plot.',
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
                   'exe' => 'python',
                   'parameters' => 'CSVppm.py'
               )
            );
			Service::create(array(
                   'name' => 'Octave: AnnotatedReceiver',
                   'description' => 'Plot the progress of receivers.',
                   'exe' => 'octave-cli',
                   'parameters' => 'SOFAAnnotatedReceiver.m'
               )
            );
			Service::create(array(
                   'name' => 'Octave: Headphones',
                   'description' => 'Plot spectra of headphones.',
                   'exe' => 'octave-cli',
                   'parameters' => 'Headphones.m'
               )
            );
			Service::create(array(
                    'name' => 'Octave: BRIR ListenerView',
                    'description' => 'Plot BRIR with ListenerView as parameter.',
                    'exe' => 'XDG_CACHE_HOME=/run/user/33/sonicom-xdg-cache-home XDG_RUNTIME_DIR=/run/user/33 xvfb-run -a octave-cli',
                    'parameters' => 'BRIRListenerView.m'
                )
            );
    }
}
