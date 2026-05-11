<?php

namespace Database\Seeders\Test;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Datasetdef;
use App\Models\Datafiletype;
use App\Models\Widget;

class DatasetdefSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		Datasetdef::create([ 'database_id' => 1, 'name' => 'dtf b', 'datafiletype_id' => Datafiletype::where('key', 'spatial-acoustics-hrtfs-sofa')->value('id'), 'widget_id' => Widget::where('key', 'properties')->value('id') ]);
		Datasetdef::create([ 'database_id' => 1, 'name' => 'hrtf b', 'datafiletype_id' => Datafiletype::where('key', 'spatial-acoustics-hrtfs-sofa')->value('id'), 'widget_id' => Widget::where('key', 'properties')->value('id') ]);
		Datasetdef::create([ 'database_id' => 1, 'name' => 'ear left', 'datafiletype_id' => Datafiletype::where('key', 'human-and-alike-image')->value('id') ]);
		Datasetdef::create([ 'database_id' => 1, 'name' => 'ear right', 'datafiletype_id' => Datafiletype::where('key', 'human-and-alike-image')->value('id') ]);
		Datasetdef::create([ 'database_id' => 2, 'name' => 'BezierPPM', 'datafiletype_id' => Datafiletype::where('key', 'human-and-alike-geometry-parametric-csv')->value('id') ]);
		Datasetdef::create([ 'database_id' => 2, 'name' => 'dtf b', 'datafiletype_id' => Datafiletype::where('key', 'spatial-acoustics-hrtfs-sofa')->value('id'), 'widget_id' => Widget::where('key', 'properties')->value('id') ]);
		Datasetdef::create([ 'database_id' => 2, 'name' => 'hrtf b', 'datafiletype_id' => Datafiletype::where('key', 'spatial-acoustics-hrtfs-sofa')->value('id'), 'widget_id' => Widget::where('key', 'properties')->value('id') ]);
		Datasetdef::create([ 'database_id' => 3, 'name' => '3DScan', 'datafiletype_id' => Datafiletype::where('key', 'spatial-acoustics-hrtfs-sofa')->value('id'), 'widget_id' => Widget::where('key', 'properties')->value('id') ]);

		Datasetdef::create([ 'database_id' => 3, 'name' => 'HRTF 48kHz', 'datafiletype_id' => Datafiletype::where('key', 'spatial-acoustics-hrtfs-sofa')->value('id'), 'widget_id' => Widget::where('key', 'properties')->value('id') ]);
		Datasetdef::create([ 'database_id' => 3, 'name' => 'HPEQ 48kHz', 'datafiletype_id' => Datafiletype::where('key', 'spatial-acoustics-hrtfs-sofa')->value('id'), 'widget_id' => Widget::where('key', 'properties')->value('id') ]);
		Datasetdef::create([ 'database_id' => 3, 'name' => 'Photogrammetry', 'datafiletype_id' => Datafiletype::where('key', 'spatial-acoustics-hrtfs-sofa')->value('id'), 'widget_id' => Widget::where('key', 'properties')->value('id') ]);
		Datasetdef::create([ 'database_id' => 4, 'name' => 'hrtf-general', 'datafiletype_id' => Datafiletype::where('key', 'spatial-acoustics-hrtfs-sofa')->value('id'), 'widget_id' => Widget::where('key', 'sofa-hrtf-general')->value('id') ]);
		Datasetdef::create([ 'database_id' => 4, 'name' => 'sofa-hrtf-1-etc', 'datafiletype_id' => Datafiletype::where('key', 'spatial-acoustics-hrtfs-sofa')->value('id'), 'widget_id' => Widget::where('key', 'sofa-brir-listenerview')->value('id') ]);
		Datasetdef::create([ 'database_id' => 4, 'name' => 'brir-general', 'datafiletype_id' => Datafiletype::where('key', 'spatial-acoustics-brirs-sofa')->value('id'), 'widget_id' => Widget::where('key', 'sofa-brir-general')->value('id') ]);
		Datasetdef::create([ 'database_id' => 4, 'name' => 'srir-general', 'datafiletype_id' => Datafiletype::where('key', 'spatial-acoustics-srirs-sofa')->value('id'), 'widget_id' => Widget::where('key', 'sofa-srir-general')->value('id') ]);
		Datasetdef::create([ 'database_id' => 4, 'name' => 'directivity-general', 'datafiletype_id' => Datafiletype::where('key', 'spatial-acoustics-directivities-sofa')->value('id'), 'widget_id' => Widget::where('key', 'sofa-directivities-polar')->value('id') ]);
		Datasetdef::create([ 'database_id' => 4, 'name' => 'sofa-properties', 'datafiletype_id' => Datafiletype::where('key', 'spatial-acoustics-general-sofa')->value('id'), 'widget_id' => Widget::where('key', 'sofa-metadata')->value('id') ]);
		Datasetdef::create([ 'database_id' => 4, 'name' => 'bezierppm', 'datafiletype_id' => Datafiletype::where('key', 'human-and-alike-geometry-non-parametric')->value('id'), 'widget_id' => Widget::where('key', 'geometry-mesh')->value('id') ]);
		Datasetdef::create([ 'database_id' => 4, 'name' => 'sofa-annotated-receiver', 'datafiletype_id' => Datafiletype::where('key', 'multisensory-explicit-spatial-data-sofa')->value('id'), 'widget_id' => Widget::where('key', 'sofa-annotated-receiver')->value('id') ]);
		Datasetdef::create([ 'database_id' => 4, 'name' => 'headphones-general', 'datafiletype_id' => Datafiletype::where('key', 'non-spatial-acoustic-data-sofa')->value('id'), 'widget_id' => Widget::where('key', 'sofa-headphones-general')->value('id') ]);
		Datasetdef::create([ 'database_id' => 4, 'name' => 'brir-listenerview', 'datafiletype_id' => Datafiletype::where('key', 'spatial-acoustics-brirs-sofa')->value('id'), 'widget_id' => Widget::where('key', 'sofa-selfone')->value('id') ]);
		Datasetdef::create([ 'database_id' => 5, 'name' => 'brir-general', 'datafiletype_id' => Datafiletype::where('key', 'spatial-acoustics-brirs-sofa')->value('id'), 'widget_id' => Widget::where('key', 'sofa-brir-general')->value('id') ]);
		Datasetdef::create([ 'database_id' => 6, 'name' => 'dtf b', 'datafiletype_id' => Datafiletype::where('key', 'spatial-acoustics-hrtfs-sofa')->value('id'), 'widget_id' => Widget::where('key', 'properties')->value('id') ]);
		Datasetdef::create([ 'database_id' => 6, 'name' => 'ear left', 'datafiletype_id' => Datafiletype::where('key', 'human-and-alike-image')->value('id') ]);
		Datasetdef::create([ 'database_id' => 7, 'name' => 'srir-general', 'datafiletype_id' => Datafiletype::where('key', 'spatial-acoustics-srirs-sofa')->value('id'), 'widget_id' => Widget::where('key', 'sofa-srir-general')->value('id') ]);
		Datasetdef::create([ 'database_id' => 8, 'name' => 'directivity-general', 'datafiletype_id' => Datafiletype::where('key', 'spatial-acoustics-directivities-sofa')->value('id'), 'widget_id' => Widget::where('key', 'sofa-directivities-polar')->value('id') ]);
		Datasetdef::create([ 'database_id' => 9, 'name' => 'sofa-properties', 'datafiletype_id' => Datafiletype::where('key', 'spatial-acoustics-general-sofa')->value('id'), 'widget_id' => Widget::where('key', 'sofa-metadata')->value('id') ]);
	}
}
