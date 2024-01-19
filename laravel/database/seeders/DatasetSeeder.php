<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Dataset;

class DatasetSeeder extends Seeder
{
    /**
     * Run the dataset seeds.
     */
    public function run(): void
    {
        Dataset::create(array('name' => 'las_nh4', 'description' => 'las_nh4', 'database_id' => 1));
        Dataset::create(array('name' => 'las_nh5', 'description' => 'las_nh5', 'database_id' => 1));
    }
}
