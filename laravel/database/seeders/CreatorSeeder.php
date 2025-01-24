<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Creator;

class CreatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       Creator::create(array(
			  'creatorName' => 'Majdak, Piotr', 
				'database_id' => 1,
        'givenName' => 'Piotr', 
        'familyName' => 'Majdak',
        'nameIdentifier' => '0000-0003-1511-6164',
        'nameIdentifierScheme' => 'ORCID',
        'creatorAffiliation' => 'Affiliation free text',
        'affiliationIdentifierScheme' => null,
        'affiliationIdentifier' => null,
        ));

       Creator::create(array(
			  'creatorName' => 'Laback, Bernhard', 
				'database_id' => 2,
        'givenName' => 'Bernhard', 
        'familyName' => 'Laback',
        'nameIdentifier' => '0000-0003-0929-6787',
        'nameIdentifierScheme' => 'ORCID',
        'creatorAffiliation' => 'Silicon Austria Labs',
        'affiliationIdentifierScheme' => 'ROR',
        'affiliationIdentifier' => 'https://ror.org/03b1qgn79',
        ));

       Creator::create(array(
			  'creatorName' => 'Somebody from Austrian Standards', 
				'database_id' => 1,
        'givenName' => null, 
        'familyName' => null,
        'nameIdentifier' => 'https://ror.org/04xer1p89',
        'nameIdentifierScheme' => 'ROR',
        'creatorAffiliation' => null,
        'affiliationIdentifierScheme' => null,
        'affiliationIdentifier' => null,
        ));
    }
}
