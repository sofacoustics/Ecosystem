<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Widget;

class WidgetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Widget::create(array('name' => 'Properties',
            'description' => 'Display datafile properties.',
            'view' => 'properties',
        ));
        Widget::create(array('name' => 'SOFA: HRTF General',
            'description' => 'Plot HRTF-related figures: ETC, magnitude spectra (logarithmic and linear frequencies), ITD, and the geometry.',
            'service_id' => 1,
            'view' => 'hrtf-general',
        ));
        Widget::create(array('name' => 'Image',
            'description' => 'Display an image (PNG, JPG, WEBP, animated or static).',
            'view' => 'image',
        ));
        Widget::create(array('name' => 'SOFA: HRTF ETC',
            'description' => 'Plot ETC in the horizontal plane.',
            'service_id' => 2,
            'view' => 'sofa-hrtf-1-etc',
        ));
        Widget::create(array('name' => 'Audio',
            'description' => 'Display an audio player for the audio file.',
            'view' => 'audio',
        ));
		
		
		Widget::create(array('name' => 'SOFA: BRIR General',
            'description' => 'Plot ETC, and the geometry of the BRIR measurements.',
            'service_id' => 3,
            'view' => 'brir-general',
        ));		
		Widget::create(array('name' => 'SOFA: SRIR General',
            'description' => 'Plot the geometry, and aplitude spectra of the SRIR measurements.',
            'service_id' => 4,
            'view' => 'srir-general',
        ));		
		Widget::create(array('name' => 'SOFA: Directivities Polar',
            'description' => 'Plot the directivities as polar plots.',
            'service_id' => 5,
            'view' => 'directivity-general',
        ));		
		Widget::create(array('name' => 'SOFA: Metadata',
            'description' => 'Show SOFA Properties.',
            'service_id' => 6,
            'view' => 'sofa-properties',
        ));		
		Widget::create(array('name' => 'Geometry: Mesh',
            'description' => 'Show Geometry as a mesh.',
            'view' => 'mesh',
        ));		
		Widget::create(array('name' => 'Geometry: BezierPPM',
            'description' => 'Render a mesh with the BezierPPM.',
            'service_id' => 7,
            'view' => 'bezierppm',
        ));		
		Widget::create(array('name' => 'Image: Spherical (JPG)',
            'description' => 'Show a 360° image (interactive).',
            'view' => 'image-360',
        ));		
		Widget::create(array('name' => 'SOFA: AnnotatedReceiver',
            'description' => 'Plot the progress of receivers.',
            'service_id' => 8,
            'view' => 'sofa-annotated-receiver',
        ));		
		Widget::create(array('name' => 'SOFA: Headphones General',
            'description' => 'Plot spectra of headphones.',
            'service_id' => 9,
            'view' => 'headphones-general',
        ));		
		Widget::create(array('name' => 'SOFA: BRIR ListenerView',
            'description' => 'Plot ETC, and the geometry of the BRIR measurements with ListenerView as parameter.',
            'service_id' => 10,
            'view' => 'brir-listenerview',
        ));	
    }
}
