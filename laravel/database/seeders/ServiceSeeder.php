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
                    'name' => 'Octave: ETC & Magnitude spectrum',
                    'description' => 'Plot three figures: horizontal-plane ETC and median-plane magnitude spectra for two channels.',
                    'exe' => 'octave-cli',
                    'parameters' => 'HRIR3.m'
                )
            );
            Service::create(array(
                    'name' => 'Octave: ETC horizontal plane',
                    'description' => 'Plot ETC in the horizontal plane.',
                    'exe' => 'octave-cli',
                    'parameters' => 'HRIR1.m'
                )
            );Service::create(array(
                    'name' => 'Octave: BRIR Geometry',
                    'exe' => 'octave-cli',
                    'parameters' => 'BRIRGeometry.m'
                )
            ));Service::create(array(
                    'name' => 'Octave: SRIR Geometry',
                    'exe' => 'octave-cli',
                    'parameters' => 'SRIRGeometry.m'
                )
            ));Service::create(array(
                    'name' => 'Octave: Directivities Polar',
                    'description' => 'Plot the directivities as polar plots.',
                    'exe' => 'octave-cli',
                    'parameters' => 'DirectivityPolar.m'
                )
            ));Service::create(array(
                    'name' => 'Octave: SOFA Properties',
                    'description' => 'Show SOFA Properties.',
                    'exe' => 'octave-cli',
                    'parameters' => 'SofaProperties.m'
                )
            ));Service::create(array(
                    'name' => 'Blender: Render PPM',
                    'description' => 'Render PPM if BezierPPM, show CSV file properties otherwise.',
                    'exe' => 'python',
                    'parameters' => 'CSVppm.py'
                )
            ));Service::create(array(
                    'name' => 'Octave: AnnotatedReceiver',
                    'description' => 'Plot the progress of receivers',
                    'exe' => 'octave-cli',
                    'parameters' => 'SOFAAnnotatedReceiver.m'
                )
            ));Service::create(array(
                    'name' => 'Octave: Headphones',
                    'description' => 'Plot spectra of headphones',
                    'exe' => 'octave-cli',
                    'parameters' => 'Headphones.m'
                )
            );
    }
}
