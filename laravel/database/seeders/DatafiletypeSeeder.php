<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Datafiletype;

class DatafiletypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
       Datafiletype::create([ 'name' => 'jpg', 'description' => 'A jpg image file', ]);
       Datafiletype::create([ 'name' => 'png', 'description' => 'A png image file', ]);
       Datafiletype::create([ 'name' => 'HRTF-sofa', 'description' => 'A sofa formatted HRTF file', ]);
       Datafiletype::create([ 'name' => 'BRIR-sofa', 'description' => 'A sofa formatted BRIR file', ]);
    }
}
