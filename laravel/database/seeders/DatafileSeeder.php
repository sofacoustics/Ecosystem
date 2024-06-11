<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Datafile;

class DatafileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ARI B
        Datafile::create(array('name' => 'dtf b_nh4.sofa', 'location' => NULL, 'dataset_id' => 1, 'datasetdef_id' => 1  ));
        Datafile::create(array('name' => 'hrtf b_nh4.sofa', 'location' => NULL, 'dataset_id' => 1, 'datasetdef_id' => 2 ));
        Datafile::create(array('name' => 'ear left.JPG', 'location' => NULL, 'dataset_id' => 1, 'datasetdef_id' => 3 ));
        Datafile::create(array('name' => 'ear right.JPG', 'location' => NULL, 'dataset_id' => 1, 'datasetdef_id' => 4 ));
        Datafile::create(array('name' => 'dtf b_nh5.sofa', 'location' => NULL, 'dataset_id' => 2, 'datasetdef_id' => 1 ));
        Datafile::create(array('name' => 'hrtf b_nh5.sofa', 'location' => NULL, 'dataset_id' => 2, 'datasetdef_id' => 2 ));
        Datafile::create(array('name' => 'ear left.JPG', 'location' => NULL, 'dataset_id' => 2, 'datasetdef_id' => 3 ));
        Datafile::create(array('name' => 'ear right.JPG', 'location' => NULL, 'dataset_id' => 2, 'datasetdef_id' => 4 ));
        Datafile::create(array('name' => 'BezierPPM.csv', 'location' => NULL, 'dataset_id' => 3, 'datasetdef_id' => 5 ));
        Datafile::create(array('name' => 'dtf b_nh4.sofa', 'location' => NULL, 'dataset_id' => 3, 'datasetdef_id' => 6 ));
        Datafile::create(array('name' => 'hrtf b_nh4.sofa', 'location' => NULL, 'dataset_id' => 3, 'datasetdef_id' => 7 ));
    }
}
