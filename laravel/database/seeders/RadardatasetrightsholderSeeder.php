<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Radardatasetrightsholder;

class RadardatasetrightsholderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Radardatasetrightsholder::create(array('value' => 'Stuefer', 'name_identifier_scheme' => 'OTHER', 'radardataset_id' => 1));
        Radardatasetrightsholder::create(array('value' => 'Majdak', 'name_identifier_scheme' => 'OTHER', 'radardataset_id' => 1));
    }
}
