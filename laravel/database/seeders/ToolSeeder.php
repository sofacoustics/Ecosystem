<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Tool;

class ToolSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		 Tool::create(array(
			'title' => 'AMT 1.6.0', 
			'description' => 'The Auditory Modeling Toolbox 1.6.0', 
			'filename' => 'amtoolbox-full-1.6.0.zip',
			'controlledrights' => 68,
			'publicationyear' => 'unknown',
			'language' => 'eng',
			'resourcetype' => 3,
			'user_id' => 1,
			));

		 Tool::create(array(
			'title' => 'SOFA Toolbox', 
			'description' => 'The SOFA Toolbox', 
			'filename' => 'sofa.zip',
			'controlledrights' => 68,
			'publicationyear' => 'unknown',
			'language' => 'eng',
			'resourcetype' => 3,
			'user_id' => 4,
			));
	}
}
