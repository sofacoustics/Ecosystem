<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Dataset;

class DatasetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Dataset::create(array('name' => 'database 1 dataset 1', 'description' => 'the first dataset', 'database_id' => 1));
        Dataset::create(array('name' => 'database 1 dataset 2', 'description' => 'the second dataset', 'database_id' => 1));
        Dataset::create(array('name' => 'database 2 dataset 1', 'description' => 'the first dataset of the second database', 'database_id' => 2));
    }
}
