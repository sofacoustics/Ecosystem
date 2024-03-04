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
       Datafiletype::create([ 'name' => 'HRTF.sofa', 'description' => 'A HRTF file in SOFA format', ]);
       Datafiletype::create([ 'name' => 'CSV', 'description' => 'A comma separated value file', ]);
    }
}
