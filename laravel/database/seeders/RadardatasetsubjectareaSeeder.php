<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Radardatasetsubjectarea;

class RadardatasetsubjectareaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Radardatasetsubjectarea::create(array('controlled_subject_area_name' => 'Other', 'additional_subject_area_name' => 'Acoustics', 'radardataset_id' => 1));
        Radardatasetsubjectarea::create(array('controlled_subject_area_name' => 'Other', 'additional_subject_area_name' => 'Mathematics', 'radardataset_id' => 1));
        Radardatasetsubjectarea::create(array('controlled_subject_area_name' => 'Other', 'additional_subject_area_name' => 'Acoustics', 'radardataset_id' => 2));
    }
}
