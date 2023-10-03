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
        Dataset::create(array('title' => 'A local dataset', 'description' => 'A truly unique dataset, I assure you!', 'uploader_id' => 1));
        Dataset::create(array('title' => 'Jonnie\'s ARI SOFA test 1', 'description' => 'Not much to say about this dataset.', 'uploader_id' => 1, 'radar_id' => 'iqcCQbvmGzYxYUne'));
        Dataset::create(array('title' => 'Jonnie\'s ARI SOFA test 2', 'description' => 'Nr. 2. \'Nuf said!', 'uploader_id' => 1, 'radar_id' => 'dEZxRRrxpiHSzbBZ'));
    }
}
