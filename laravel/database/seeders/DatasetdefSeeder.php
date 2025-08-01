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
		Datasetdef::create([ 'database_id' => 4, 'name' => 'hrtf-general', 'datafiletype_id' => 1, 'widget_id' => 2 ]);
		Datasetdef::create([ 'database_id' => 4, 'name' => 'sofa-hrtf-1-etc', 'datafiletype_id' => 1, 'widget_id' => 4 ]);
		Datasetdef::create([ 'database_id' => 4, 'name' => 'brir-general', 'datafiletype_id' => 2, 'widget_id' => 6 ]);
		Datasetdef::create([ 'database_id' => 4, 'name' => 'srir-general', 'datafiletype_id' => 3, 'widget_id' => 7 ]);
		Datasetdef::create([ 'database_id' => 4, 'name' => 'directivity-general', 'datafiletype_id' => 4, 'widget_id' => 8 ]);
		Datasetdef::create([ 'database_id' => 4, 'name' => 'sofa-properties', 'datafiletype_id' => 5, 'widget_id' => 9 ]);
		Datasetdef::create([ 'database_id' => 4, 'name' => 'bezierppm', 'datafiletype_id' => 6, 'widget_id' => 10 ]);
		Datasetdef::create([ 'database_id' => 4, 'name' => 'sofa-annotated-receiver', 'datafiletype_id' => 12, 'widget_id' => 13 ]);
		Datasetdef::create([ 'database_id' => 4, 'name' => 'headphones-general', 'datafiletype_id' => 14, 'widget_id' => 14 ]);
		Datasetdef::create([ 'database_id' => 4, 'name' => 'brir-listenerview', 'datafiletype_id' => 2, 'widget_id' => 15 ]);
		Datasetdef::create([ 'database_id' => 5, 'name' => 'brir-general', 'datafiletype_id' => 2, 'widget_id' => 6 ]);
		Datasetdef::create([ 'database_id' => 6, 'name' => 'dtf b', 'datafiletype_id' => 1, 'widget_id' => 1 ]);
		Datasetdef::create([ 'database_id' => 6, 'name' => 'ear left', 'datafiletype_id' => 8 ]);
		Datasetdef::create([ 'database_id' => 7, 'name' => 'srir-general', 'datafiletype_id' => 3, 'widget_id' => 7 ]);
		Datasetdef::create([ 'database_id' => 8, 'name' => 'directivity-general', 'datafiletype_id' => 4, 'widget_id' => 8 ]);
		Datasetdef::create([ 'database_id' => 9, 'name' => 'sofa-properties', 'datafiletype_id' => 5, 'widget_id' => 9 ]);

	}
}
