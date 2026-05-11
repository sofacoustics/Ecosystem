<?php

namespace Database\Seeders\Test;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Database;

class DatabaseSeeder extends Seeder
{
	/**
	 * Seed the application's database.
	 */
	public function run(): void
	{

		Database::create(array(
			'title' => 'ARI B',
			'additionaltitle' => 'Additional Title',
			'descriptiongeneral' => 'Specialty: hrtfs, dtfs, and photos of the ears',
			'productionyear' => '2024-2025',
			'publicationyear' => 'unknown',
			'language' => 'eng',
			'resourcetype' => null,
			'datasources' => 'Origin of the data contained in the resource.',
			'software' => 'Software version e.g. 1.3',
			'processing' => 'Instructions used for processing the data in the digital resource (e.g. statistics).',
			'relatedinformation' => 'Related information on the sample used to produce the digital data in the resource (e. g. Database ID, registry number, GenBank, IntEnz, PubChem, MedGen, PMID, PDB, molecular formula).',
			'controlledrights' => (\App\Models\Metadataschema::where('name', 'controlledRights')->where('value', 'CC_BY_4_0_ATTRIBUTION')->first()->id),
			'additionalrights' => null,
			'radardataset' => null,
			'user_id' => 2));
		Database::create(array('title' => 'ARI BezierPPM',
			'additionaltitle' => null,
			'descriptiongeneral' => 'Specialty: csv, dtfs and hrtfs',
			'productionyear' => '2024-2025',
			'publicationyear' => 'unknown',
			'language' => 'eng',
			'resourcetype' => null,
			'datasources' => null,
			'software' => null,
			'processing' => null,
			'relatedinformation' => null,
			'controlledrights' => (\App\Models\Metadataschema::where('name', 'controlledRights')->where('value', 'CC_BY_4_0_ATTRIBUTION')->first()->id),
			'additionalrights' => null,
			'visible' => 1,
			'radardataset' => null,
			'user_id' => 1, ));
		Database::create(array('title' => 'AXD',
			'additionaltitle' => null,
			'descriptiongeneral' => 'Specialty: SONICOM Database',
			'productionyear' => 'unknown',
			'publicationyear' => 'unknown',
			'language' => 'eng',
			'resourcetype' => null,
			'resource' => null,
			'datasources' => null,
			'software' => null,
			'processing' => null,
			'relatedinformation' => null,
			'controlledrights' => (\App\Models\Metadataschema::where('name', 'controlledRights')->where('value', 'CC_BY_4_0_ATTRIBUTION')->first()->id),
			'additionalrights' => null,
			'visible' => 1,
			'radardataset' => null,
			'user_id' => 4, ));
		Database::create(array('title' => 'Test Services',
			'additionaltitle' => null,
			'productionyear' => '2025',
			'publicationyear' => 'unknown',
			'language' => 'eng',
			'resourcetype' => null,
			'resource' => null,
			'datasources' => null,
			'software' => null,
			'processing' => null,
			'relatedinformation' => null,
			'controlledrights' => (\App\Models\Metadataschema::where('name', 'controlledRights')->where('value', 'CC_BY_4_0_ATTRIBUTION')->first()->id),
			'additionalrights' => null,
			'visible' => 1,
			'radardataset' => null,
			'user_id' => 4, ));
		Database::create(array('title' => 'Test brir-general',
			'additionaltitle' => null,
			'productionyear' => '2025',
			'publicationyear' => 'unknown',
			'language' => 'eng',
			'resourcetype' => null,
			'resource' => null,
			'datasources' => null,
			'software' => null,
			'processing' => null,
			'relatedinformation' => null,
			'controlledrights' => (\App\Models\Metadataschema::where('name', 'controlledRights')->where('value', 'CC_BY_4_0_ATTRIBUTION')->first()->id),
			'additionalrights' => null,
			'radardataset' => null,
			'user_id' => 4, ));
		Database::create(array(
			'title' => 'ARI',
			'additionaltitle' => 'Additional Title',
			'descriptiongeneral' => 'Specialty: hrtfs, dtfs, and photos of the ears',
			'productionyear' => '2024-2025',
			'publicationyear' => 'unknown',
			'language' => 'eng',
			'resourcetype' => null,
			'datasources' => 'Origin of the data contained in the resource.',
			'software' => 'Software version e.g. 1.3',
			'processing' => 'Instructions used for processing the data in the digital resource (e.g. statistics).',
			'relatedinformation' => 'Related information on the sample used to produce the digital data in the resource (e. g. Database ID, registry number, GenBank, IntEnz, PubChem, MedGen, PMID, PDB, molecular formula).',
			'controlledrights' => (\App\Models\Metadataschema::where('name', 'controlledRights')->where('value', 'OTHER')->first()->id),
			'additionalrights' => null,
			'visible' => 1,
			'radardataset' => null,
			'user_id' => 4)
			);
		Database::create(array('title' => 'Test srir-general',
			'additionaltitle' => null,
			'productionyear' => '2025',
			'publicationyear' => 'unknown',
			'language' => 'eng',
			'resourcetype' => null,
			'resource' => null,
			'datasources' => null,
			'software' => null,
			'processing' => null,
			'relatedinformation' => null,
			'controlledrights' => (\App\Models\Metadataschema::where('name', 'controlledRights')->where('value', 'CC_BY_4_0_ATTRIBUTION')->first()->id),
			'additionalrights' => null,
			'radardataset' => null,
			'user_id' => 4, )
			);
		Database::create(array('title' => 'Test directivity-general',
			'additionaltitle' => null,
			'productionyear' => '2025',
			'publicationyear' => 'unknown',
			'language' => 'eng',
			'resourcetype' => null,
			'resource' => null,
			'datasources' => null,
			'software' => null,
			'processing' => null,
			'relatedinformation' => null,
			'controlledrights' => (\App\Models\Metadataschema::where('name', 'controlledRights')->where('value', 'CC_BY_4_0_ATTRIBUTION')->first()->id),
			'additionalrights' => null,
			'radardataset' => null,
			'user_id' => 4, )
			);			
		Database::create(array('title' => 'Test sofa-properties',
			'additionaltitle' => null,
			'productionyear' => '2025',
			'publicationyear' => 'unknown',
			'language' => 'eng',
			'resourcetype' => null,
			'resource' => null,
			'datasources' => null,
			'software' => null,
			'processing' => null,
			'relatedinformation' => null,
			'controlledrights' => (\App\Models\Metadataschema::where('name', 'controlledRights')->where('value', 'CC_BY_4_0_ATTRIBUTION')->first()->id),
			'additionalrights' => null,
			'radardataset' => null,
			'user_id' => 4, )
			);

	}
}
