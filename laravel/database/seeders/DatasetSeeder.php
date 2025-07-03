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
        Dataset::create(array('name' => 'nh4', 'description' => 'normal hearer 4', 'database_id' => 1));
        Dataset::create(array('name' => 'nh5', 'description' => 'normal hearer 5', 'database_id' => 1));
        Dataset::create(array('name' => 'nh4', 'description' => 'normal hearer 4', 'database_id' => 2));
		Dataset::create(array('name' => 'test services', 'description' => 'test services', 'database_id' => 4));
        Dataset::create(array('name' => 'test service brir-general', 'description' => 'test service 3', 'database_id' => 5));
        Dataset::create(array('name' => 'test service srir-general', 'description' => 'test service 4', 'database_id' => 7));
        Dataset::create(array('name' => 'test service directivity-general', 'description' => 'test service 5', 'database_id' => 8));
		Dataset::create(array('name' => 'test service sofa-properties', 'description' => 'test service 6', 'database_id' => 9));
    }
}
