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
				'commentable_id' => 1,
				'commentable_type' => 'App\Models\Database',
        'controlledSubjectAreaIndex' => 15,
        'additionalSubjectArea' => null,
        ));

       SubjectArea::create(array(
				'commentable_id' => 1,
				'commentable_type' => 'App\Models\Database',
        'controlledSubjectAreaIndex' => 46,
        'additionalSubjectArea' => 'sonicom ecosystem',
        ));

       SubjectArea::create(array(
				'commentable_id' => 2,
				'commentable_type' => 'App\Models\Database',
        'controlledSubjectAreaIndex' => 40,
        'additionalSubjectArea' => null,
        ));
    }
}
