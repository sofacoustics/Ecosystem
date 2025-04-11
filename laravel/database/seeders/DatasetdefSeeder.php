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
		Datasetdef::create([ 'database_id' => 1, 'name' => 'dtf b', 'datafiletype_id' => 1, 'widget_id' => 1 ]);
		Datasetdef::create([ 'database_id' => 1, 'name' => 'hrtf b', 'datafiletype_id' => 1, 'widget_id' => 1 ]);
		Datasetdef::create([ 'database_id' => 1, 'name' => 'ear left', 'datafiletype_id' => 8 ]);
		Datasetdef::create([ 'database_id' => 1, 'name' => 'ear right', 'datafiletype_id' => 8 ]);
		Datasetdef::create([ 'database_id' => 2, 'name' => 'BezierPPM', 'datafiletype_id' => 7 ]);
		Datasetdef::create([ 'database_id' => 2, 'name' => 'dtf b', 'datafiletype_id' => 1, 'widget_id' => 1 ]);
		Datasetdef::create([ 'database_id' => 2, 'name' => 'hrtf b', 'datafiletype_id' => 1, 'widget_id' => 1 ]);
		Datasetdef::create([ 'database_id' => 3, 'name' => '3DScan', 'datafiletype_id' => 1, 'widget_id' => 1 ]);
		Datasetdef::create([ 'database_id' => 3, 'name' => 'HRTF 48kHz', 'datafiletype_id' => 1, 'widget_id' => 1 ]);
		Datasetdef::create([ 'database_id' => 3, 'name' => 'HPEQ 48kHz', 'datafiletype_id' => 1, 'widget_id' => 1 ]);
		Datasetdef::create([ 'database_id' => 3, 'name' => 'Photogrammetry', 'datafiletype_id' => 1, 'widget_id' => 1 ]);
		
	}
}
