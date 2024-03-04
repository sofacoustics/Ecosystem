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
        Datafile::create(array('name' => 'dtf b_nh4.sofa', 'dataset_id' => 1, 'datafiletype_id' => 3  ));
        Datafile::create(array('name' => 'hrtf b_nh4.sofa', 'dataset_id' => 1, 'datafiletype_id' => 3 ));
        Datafile::create(array('name' => 'ear right.JPG', 'dataset_id' => 1, 'datafiletype_id' => 1 ));
        Datafile::create(array('name' => 'ear left.JPG', 'dataset_id' => 1, 'datafiletype_id' => 1 ));
        Datafile::create(array('name' => 'dtf b_nh5.sofa', 'dataset_id' => 2, 'datafiletype_id' => 3 ));
        Datafile::create(array('name' => 'hrtf b_nh5.sofa', 'dataset_id' => 2, 'datafiletype_id' => 3 ));
        Datafile::create(array('name' => 'ear right.JPG', 'dataset_id' => 2, 'datafiletype_id' => 1 ));
        Datafile::create(array('name' => 'ear left.JPG', 'dataset_id' => 2, 'datafiletype_id' => 1 ));
        Datafile::create(array('name' => 'BezierPPM.csv', 'dataset_id' => 3, 'datafiletype_id' => 4 ));
        Datafile::create(array('name' => 'dtf b_nh4', 'dataset_id' => 3, 'datafiletype_id' => 3 ));
        Datafile::create(array('name' => 'hrtf b_nh4', 'dataset_id' => 3, 'datafiletype_id' => 3 ));
    }
}
