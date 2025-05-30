<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Data\RadardatasetpureData;

use App\Models\MenuItem;
use App\Models\Database;
use App\Models\Radardatasetrightsholder;
use App\Models\Radar\Metadataschema;

class DatabaseSeeder extends Seeder
{
	/**
	 * Seed the application's database.
	 */
	public function run(): void
	{
		$this->call(RolesAndPermissionsSeeder::class);
		$this->call(UserSeeder::class);
		Database::create(array(
			'title' => 'ARI B',
			'additionaltitle' => 'Additional Title',
			'description' => 'Specialty: hrtfs, dtfs, and photos of the ears',
			'productionyear' => '2024-2025',
			'publicationyear' => 'unknown',
			'language' => 'eng',
			'resourcetype' => null,
			'resource' => 'General information on the content of the resource.',
			'datasources' => 'Origin of the data contained in the resource.',
			'software' => 'Software version e.g. 1.3',
			'processing' => 'Instructions used for processing the data in the digital resource (e.g. statistics).',
			'relatedinformation' => 'Related information on the sample used to produce the digital data in the resource (e. g. Database ID, registry number, GenBank, IntEnz, PubChem, MedGen, PMID, PDB, molecular formula).',
			'controlledrights' => 68,
			'additionalrights' => 'My own license',
			'radardataset' => null,
			'user_id' => 2));
		Database::create(array('title' => 'ARI BezierPPM',
			'additionaltitle' => null,
			'description' => 'Specialty: csv, dtfs and hrtfs',
			'productionyear' => '2024-2025',
			'publicationyear' => 'unknown',
			'language' => 'eng',
			'resourcetype' => null,
			'resource' => "SONICOM Ecosystem",
			'datasources' => null,
			'software' => null,
			'processing' => null,
			'relatedinformation' => null,
			'controlledrights' => 47,
			'additionalrights' => null,
			'visible' => 1,
			'radardataset' => null,
			'user_id' => 1, ));
		Database::create(array('title' => 'AXD',
			'additionaltitle' => null,
			'description' => 'Specialty: SONICOM Database',
			'productionyear' => 'unknown',
			'publicationyear' => 'unknown',
			'language' => 'eng',
			'resourcetype' => null,
			'resource' => null,
			'datasources' => null,
			'software' => null,
			'processing' => null,
			'relatedinformation' => null,
			'controlledrights' => 47,
			'additionalrights' => null,
			'visible' => 1,
			'radardataset' => null,
			'user_id' => 4, ));
		Database::create(array('title' => 'Test Services',
			'additionaltitle' => null,
			'description' => 'Specialty: SONICOM Database',
			'productionyear' => '2025',
			'publicationyear' => 'unknown',
			'language' => 'eng',
			'resourcetype' => null,
			'resource' => null,
			'datasources' => null,
			'software' => null,
			'processing' => null,
			'relatedinformation' => null,
			'controlledrights' => 47,
			'additionalrights' => null,
			'visible' => 1,
			'radardataset' => null,
			'user_id' => 4, ));
		Database::create(array('title' => 'Test sofa-brir-geometry',
			'additionaltitle' => null,
			'description' => 'Specialty: SONICOM Database',
			'productionyear' => '2025',
			'publicationyear' => 'unknown',
			'language' => 'eng',
			'resourcetype' => null,
			'resource' => null,
			'datasources' => null,
			'software' => null,
			'processing' => null,
			'relatedinformation' => null,
			'controlledrights' => 47,
			'additionalrights' => null,
			'radardataset' => null,
			'user_id' => 4, ));
		Database::create(array(
			'title' => 'ARI',
			'additionaltitle' => 'Additional Title',
			'description' => 'Specialty: hrtfs, dtfs, and photos of the ears',
			'productionyear' => '2024-2025',
			'publicationyear' => 'unknown',
			'language' => 'eng',
			'resourcetype' => null,
			'resource' => 'General information on the content of the resource.',
			'datasources' => 'Origin of the data contained in the resource.',
			'software' => 'Software version e.g. 1.3',
			'processing' => 'Instructions used for processing the data in the digital resource (e.g. statistics).',
			'relatedinformation' => 'Related information on the sample used to produce the digital data in the resource (e. g. Database ID, registry number, GenBank, IntEnz, PubChem, MedGen, PMID, PDB, molecular formula).',
			'controlledrights' => 68,
			'additionalrights' => 'My own license',
			'visible' => 1,
			'radardataset' => null,
			'user_id' => 4)
			);
		Database::create(array('title' => 'Test sofa-srir-geometry',
			'additionaltitle' => null,
			'description' => 'Specialty: SONICOM Database',
			'productionyear' => '2025',
			'publicationyear' => 'unknown',
			'language' => 'eng',
			'resourcetype' => null,
			'resource' => null,
			'datasources' => null,
			'software' => null,
			'processing' => null,
			'relatedinformation' => null,
			'controlledrights' => 47,
			'additionalrights' => null,
			'radardataset' => null,
			'user_id' => 4, )
			);
		Database::create(array('title' => 'Test sofa-directivity-polar',
			'additionaltitle' => null,
			'description' => 'Specialty: SONICOM Database',
			'productionyear' => '2025',
			'publicationyear' => 'unknown',
			'language' => 'eng',
			'resourcetype' => null,
			'resource' => null,
			'datasources' => null,
			'software' => null,
			'processing' => null,
			'relatedinformation' => null,
			'controlledrights' => 47,
			'additionalrights' => null,
			'radardataset' => null,
			'user_id' => 4, )
			);			
		Database::create(array('title' => 'Test sofa-properties',
			'additionaltitle' => null,
			'description' => 'Specialty: SONICOM Database',
			'productionyear' => '2025',
			'publicationyear' => 'unknown',
			'language' => 'eng',
			'resourcetype' => null,
			'resource' => null,
			'datasources' => null,
			'software' => null,
			'processing' => null,
			'relatedinformation' => null,
			'controlledrights' => 47,
			'additionalrights' => null,
			'radardataset' => null,
			'user_id' => 4, )
			);			
		
			//Database::create(array('name' => 'Jonnie\'s ARI SOFA test 2', 'description' => 'Nr. 2. \'Nuf said!', 'user_id' => 1, 'radar_id' => 'dEZxRRrxpiHSzbBZ'));
		$this->call([
			DatasetSeeder::class,
			DatafiletypeSeeder::class,
			ServiceSeeder::class,
			WidgetSeeder::class,
			DatasetdefSeeder::class,
			DatafileSeeder::class,
			MenuItemSeeder::class,
			MetadataschemaSeeder::class,
			ToolSeeder::class,
			CreatorSeeder::class,
			PublisherSeeder::class,
			SubjectAreaSeeder::class,
			RightsholderSeeder::class,
			KeywordSeeder::class,
			RelatedIdentifierSeeder::class,
			CommentSeeder::class,
			/*
			RadardatasetresourcetypeSeeder::class,
			RadardatasetsubjectareaSeeder::class,
			RadardatasetrightsholderSeeder::class,
			RadardatasetSeeder::class,
			 */
		]);

	}
}
