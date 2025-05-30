<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Tool;

class ToolSeeder extends Seeder
{
	/**
	 * Run the tool seeds.
	 */
	public function run(): void
	{
		 Tool::create(array(
			'title' => 'AMT 1.6.0', 
			'additionaltitle' => 'The Auditory Modeling Toolbox 1.6.0', 
			'filename' => 'amtoolbox-full-1.6.0.zip',
			'controlledrights' => (\App\Models\Radar\Metadataschema::where('name', 'controlledRights')->where('value', 'CC_BY_4_0_ATTRIBUTION')->first()->id),
			'publicationyear' => 'unknown',
			'language' => 'eng',
			'resourcetype' => (\App\Models\Radar\Metadataschema::where('name', 'resourcetype')->where('value', 'SOFTWARE')->first()->id),
			'user_id' => 2,
			));

		 Tool::create(array(
			'title' => 'SOFA Toolbox', 
			'additionaltitle' => 'Reference SOFA Toolbox for Matlab and Octave', 
			'filename' => 'sofa.zip',
			'controlledrights' => (\App\Models\Radar\Metadataschema::where('name', 'controlledRights')->where('value', 'CC_BY_4_0_ATTRIBUTION')->first()->id),
			'publicationyear' => 'unknown',
			'language' => 'eng',
			'resourcetype' => (\App\Models\Radar\Metadataschema::where('name', 'resourcetype')->where('value', 'SOFTWARE')->first()->id),
			'user_id' => 4,
			));
	}
}
