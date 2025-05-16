<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Keyword;

class KeywordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       Keyword::create(array(
			  'keywordName' => 'Majdak, Piotr', 
				'commentable_id' => 1,
				'commentable_type' => 'App\Models\Database',
        'keywordSchemeIndex' => 0, // 0: Other, 1: GND
        'schemeURI' => null,
        'valueURI' => null,
        'classificationCode' => 'he?',
        ));

       Keyword::create(array(
			  'keywordName' => 'Forschungsdaten', 
				'commentable_id' => 2,
				'commentable_type' => 'App\Models\Database',
        'keywordSchemeIndex' => 1,
        'schemeURI' => 'https://d-nb.info/gnd/',
        'valueURI' => 'https://d-nb.info/gnd/1098579690',
        'classificationCode' => '1098579690',
        ));

       Keyword::create(array(
			  'keywordName' => 'Something', 
				'commentable_id' => 1,
				'commentable_type' => 'App\Models\Database',
        'keywordSchemeIndex' => 1,
        'schemeURI' => 'https://ror.org/04xer1p89',
        'valueURI' => '04xer1p89',
        'classificationCode' => null,
        ));
    }
}
