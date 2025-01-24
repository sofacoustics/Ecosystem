<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Tool;

class ToolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       Tool::create(array(
			  'name' => 'AMT 1.6.0', 
        'description' => 'The Auditory Modeling Toolbox 1.6.0', 
        'radar_id' => null,
        'filename' => 'amtoolbox-full-1.6.0.zip',
        'linkdoc' => 'https://amtoolbox.org/amt-1.6.0/doc/base/index.php',
        'linkcode' => 'https://sourceforge.net/p/amtoolbox/code/ci/develop/tree/',
        ));

       Tool::create(array(
			  'name' => 'SOFA Toolbox', 
        'description' => 'The SOFA Toolbox', 
        'radar_id' => null,
        'filename' => null,
        'linkdoc' => null,
        'linkcode' => null,
        ));
    }
}
