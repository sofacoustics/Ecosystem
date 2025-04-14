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
		Datasetdef::create([ 'database_id' => 4, 'name' => 'sofa-brir-geometry', 'datafiletype_id' => 2, 'widget_id' => 3 ]);
		Datasetdef::create([ 'database_id' => 4, 'name' => 'sofa-srir-geometry', 'datafiletype_id' => 3, 'widget_id' => 4 ]);
		Datasetdef::create([ 'database_id' => 4, 'name' => 'sofa-directivity-polar', 'datafiletype_id' => 4, 'widget_id' => 5 ]);
		Datasetdef::create([ 'database_id' => 4, 'name' => 'sofa-properties', 'datafiletype_id' => 5, 'widget_id' => 6 ]);
		Datasetdef::create([ 'database_id' => 4, 'name' => 'bezierppm', 'datafiletype_id' => 7, 'widget_id' => 7 ]);
		Datasetdef::create([ 'database_id' => 4, 'name' => 'sofa-annotated-receiver', 'datafiletype_id' => 14, 'widget_id' => 8 ]);
		Datasetdef::create([ 'database_id' => 4, 'name' => 'sofa-headphones', 'datafiletype_id' => 12, 'widget_id' => 9 ]);
	}
}
