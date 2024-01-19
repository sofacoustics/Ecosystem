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
        Datafile::create(array('name' => 'A png file', 'dataset_id' => 1, 'datafiletype_id' => 1 ));
        Datafile::create(array('name' => 'A jpg file', 'dataset_id' => 1, 'datafiletype_id' => 2 ));
    }
}
