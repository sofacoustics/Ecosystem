<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Rightsholder;

class RightsholderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       Rightsholder::create(array(
			  'rightsholderName' => 'Piotr/ARI/ÖAW', 
				'commentable_id' => 2,
				'commentable_type' => 'App\Models\Database',
        'nameIdentifierSchemeIndex' => 1,
        'nameIdentifier' => '0000-0003-1511-6164',
        'schemeURI' => 'https://orcid.org/0000-0003-1511-6164',
        ));

       Rightsholder::create(array(
			  'rightsholderName' => 'Verlag/ÖAW', 
				'commentable_id' => 1,
				'commentable_type' => 'App\Models\Database',
        'nameIdentifier' => 'keine Ahnung',
        'nameIdentifierSchemeIndex' => 0,
        'schemeURI' => 'https://oeaw.ac.at/',
        ));

       Rightsholder::create(array(
			  'rightsholderName' => 'Austrian Standards', 
				'commentable_id' => 2,
				'commentable_type' => 'App\Models\Database',
        'nameIdentifier' => 'https://ror.org/04xer1p89',
        'nameIdentifierSchemeIndex' => 2,
        'schemeURI' => 'https://ror.org/',
        ));
    }
}
