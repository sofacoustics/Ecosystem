<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Datafiletype_Datasetdef extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       \DB::table('datafiletype_datasetdef')->insert(array(
        array('datafiletype_id' => 1, 'datasetdef_id' => 1),
        array('datafiletype_id' => 2, 'datasetdef_id' => 1)
       ));
    }
}
