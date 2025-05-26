<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\SubjectArea;
class SubjectAreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       SubjectArea::create(array(
				'subjectareaable_id' => 1,
				'subjectareaable_type' => 'App\Models\Database',
        'controlledSubjectAreaIndex' => (\App\Models\Radar\Metadataschema::where('name', 'subjectArea')->first()->id), // Agriculture, first entry
        'additionalSubjectArea' => null,
        ));

       SubjectArea::create(array(
				'subjectareaable_id' => 1,
				'subjectareaable_type' => 'App\Models\Database',
        'controlledSubjectAreaIndex' => (\App\Models\Radar\Metadataschema::where('name', 'subjectArea')->where('value', 'OTHER')->first()->id), // Other, last entry
        'additionalSubjectArea' => 'sonicom ecosystem',
        ));

       SubjectArea::create(array(
				'subjectareaable_id' => 2,
				'subjectareaable_type' => 'App\Models\Database',
        'controlledSubjectAreaIndex' => (\App\Models\Radar\Metadataschema::where('name', 'subjectArea')->where('value', 'PSYCHOLOGY')->first()->id),
        'additionalSubjectArea' => null,
        ));
    }
}
