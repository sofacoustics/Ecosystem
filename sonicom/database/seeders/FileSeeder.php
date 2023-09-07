<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\File;

class FileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        File::create(array('name' => 'file nr 1', 'dataset_id' => 1 ));
        File::create(array('name' => 'file nr 2', 'dataset_id' => 1 ));
    }
}
