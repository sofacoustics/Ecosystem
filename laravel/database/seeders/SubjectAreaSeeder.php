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
				'database_id' => 1,
        'controlledSubjectAreaIndex' => 15,
        'additionalSubjectArea' => null,
        ));

       SubjectArea::create(array(
				'database_id' => 1,
        'controlledSubjectAreaIndex' => 46,
        'additionalSubjectArea' => 'sonicom ecosystem',
        ));

       SubjectArea::create(array(
				'database_id' => 2,
        'controlledSubjectAreaIndex' => 40,
        'additionalSubjectArea' => null,
        ));
    }
}
