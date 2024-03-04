<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Datasetdef;

class DatasetdefSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Datasetdef::create([ 'database_id' => 1, 'name' => 'dtf b', 'datafiletype_id' => 3 ]);
        Datasetdef::create([ 'database_id' => 1, 'name' => 'hrtf b', 'datafiletype_id' => 3 ]);
        Datasetdef::create([ 'database_id' => 1, 'name' => 'ear left', 'datafiletype_id' => 1 ]);
        Datasetdef::create([ 'database_id' => 1, 'name' => 'ear right', 'datafiletype_id' => 1 ]);
        Datasetdef::create([ 'database_id' => 2, 'name' => 'BezierPPM', 'datafiletype_id' => 4 ]);
        Datasetdef::create([ 'database_id' => 2, 'name' => 'dtf b', 'datafiletype_id' => 3 ]);
        Datasetdef::create([ 'database_id' => 2, 'name' => 'hrtf b', 'datafiletype_id' => 3 ]);

    }
}
