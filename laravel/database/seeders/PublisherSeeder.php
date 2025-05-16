<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Publisher;

class PublisherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       Publisher::create(array(
			  'publisherName' => 'Piotr/ARI/ÖAW', 
				'commentable_id' => 1,
				'commentable_type' => 'App\Models\Database',
        'nameIdentifierSchemeIndex' => 1,
        'nameIdentifier' => '0000-0003-1511-6164',
        'schemeURI' => 'https://orcid.org/0000-0003-1511-6164',
        ));

       Publisher::create(array(
			  'publisherName' => 'Verlag/ÖAW', 
				'commentable_id' => 2,
				'commentable_type' => 'App\Models\Database',
        'nameIdentifier' => 'keine Ahnung',
        'nameIdentifierSchemeIndex' => 0,
        'schemeURI' => 'https://oeaw.ac.at/',
        ));

       Publisher::create(array(
			  'publisherName' => 'Austrian Standards', 
				'commentable_id' => 1,
				'commentable_type' => 'App\Models\Database',
        'nameIdentifier' => 'https://ror.org/04xer1p89',
        'nameIdentifierSchemeIndex' => 2,
        'schemeURI' => 'https://ror.org/',
        ));
    }
}
