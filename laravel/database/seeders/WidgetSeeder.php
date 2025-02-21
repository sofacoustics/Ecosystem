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
            'description' => 'Display datafile properties',
            'view' => 'properties',
        ));
        Widget::create(array('name' => 'HRTF - ETC and Magnitude (3 images)',
            'description' => 'Plot and display ETC horizontal plane, magnitude spectrum in the median plane, channel 2, and non-normalized magnitude spectrum in the median plane, channel 1 using Octave script CreateFigures.m',
            'service_id' => 1,
            'view' => 'hrtf-3-images',
        ));
        Widget::create(array('name' => 'Image',
            'description' => 'Display an image',
            'view' => 'image',
        ));
        Widget::create(array('name' => 'HRTF - ETC (1 image)',
            'description' => 'Plot and display ETC horizontal plane using Octave CreateFigures.m script',
            'service_id' => 2,
            'view' => 'hrtf-1-image',
        ));
    }
}
