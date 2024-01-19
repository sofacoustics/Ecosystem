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
        Datafile::create(array('name' => 'hrtf las_nh4.sofa', 'dataset_id' => 1, 'datafiletype_id' => 3 ));
        Datafile::create(array('name' => 'dtf las_nh4.sofa', 'dataset_id' => 1, 'datafiletype_id' => 5 ));
        Datafile::create(array('name' => 'hrtf las_nh5.sofa', 'dataset_id' => 2, 'datafiletype_id' => 3 ));
        Datafile::create(array('name' => 'dtf las_nh5.sofa', 'dataset_id' => 2, 'datafiletype_id' => 5 ));
    }
}
