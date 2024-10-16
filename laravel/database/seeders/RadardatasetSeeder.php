<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


use App\Models\Radardataset;

class RadardatasetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Radardataset::create(array('title' => 'ARI B RADAR dataset', 'database_id' => 1));
        Radardataset::create(array('title' => 'ARI BezierPPM RADAR dataset', 'database_id' => 2));
    }
}
