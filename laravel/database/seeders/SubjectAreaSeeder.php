<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\SubjectArea;
use App\Models\Database;
use App\Models\Tool;

class SubjectAreaSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
			// Insert two subject areas for each database
		$databases = Database::get(); // get all databases
		foreach($databases as $database)
		{
			SubjectArea::create(array(
				'subjectareaable_id' => $database->id,
				'subjectareaable_type' => 'App\Models\Database',
				'controlledSubjectAreaIndex' => (\App\Models\Metadataschema::where('name', 'subjectArea')->where('value', 'LIFE_SCIENCE')->first()->id), // Life science
				'additionalSubjectArea' => null,
				));

			SubjectArea::create(array(
				'subjectareaable_id' => $database->id,
				'subjectareaable_type' => 'App\Models\Database',
				'controlledSubjectAreaIndex' => (\App\Models\Metadataschema::where('name', 'subjectArea')->where('value', 'OTHER')->first()->id), // Other
				'additionalSubjectArea' => 'SONICOM Ecosystem',
				));
		}

			// Insert two subject areas for each tool
		$tools = Tool::get(); // get all tools
		foreach($tools as $tool)
		{
			SubjectArea::create(array(
				'subjectareaable_id' => $tool->id,
				'subjectareaable_type' => 'App\Models\Tool',
				'controlledSubjectAreaIndex' => (\App\Models\Metadataschema::where('name', 'subjectArea')->where('value', 'LIFE_SCIENCE')->first()->id), // Life science
				'additionalSubjectArea' => null,
				));

			SubjectArea::create(array(
				'subjectareaable_id' => $tool->id,
				'subjectareaable_type' => 'App\Models\Tool',
				'controlledSubjectAreaIndex' => (\App\Models\Metadataschema::where('name', 'subjectArea')->where('value', 'OTHER')->first()->id), // Other
				'additionalSubjectArea' => 'SONICOM Ecosystem',
				));
		}

	}
}
