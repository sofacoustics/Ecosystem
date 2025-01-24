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
       Widget::create(array('name' => 'Octave HRTF', 
        'description' => 'Generate HRTF images from HRTF SOFA files using octave script ...', 
        'scriptname' => 'CreateFigures.m',
        'scriptpath' => 'jw:todo',
        'scriptparameters' => 'jw:todo',
        'functionname' => 'CreateFigures',
        'externalurl' => null
        ));
       Widget::create(array('name' => 'Octave BRIR', 
        'description' => 'Generate ... for BRIR files', 
        'scriptname' => 'jw:todo',
        'scriptpath' => 'jw:todo',
        'scriptparameters' => 'jw:todo',
        'externalurl' => null
    ));
        //
    }
}
