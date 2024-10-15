<?php
/*
 *
 * laravel-data object for RADAR 'Creator'
 *
 * See https://radar.products.fiz-karlsruhe.de/de/radarfeatures/radar-api
 */

/* Example XML from RADAR export.
<creator>                                                                                                                 
    <creatorName>Person(s) responsible for the content of the research data e.g. the data producer. Format: family name, first (given) name.</creatorName>                                                                                              
    <givenName>GivenName</givenName>                                                                                      
    <familyName>FamilyName</familyName>                                                                                   
    <nameIdentifier schemeURI="http://orcid.org/" nameIdentifierScheme="ORCID">0000-0002-6368-1929</nameIdentifier>       
    <creatorAffiliation schemeURI="https://ror.org/" affiliationIdentifierScheme="ROR" affiliationIdentifier="https://ror.org/0387prb75">Affiliation: name of the institution.</creatorAffiliation>                                                     
    </creator>
 */
namespace App\Data;

use Spatie\LaravelData\Data;

class RadarcreatorData extends Data
{
    public function __construct(
        // mandatory fields
        public string $givenName,
        public string $familyName,
        public ?string $nameIdentifier = null,
        public ?string $creatorAffiliation = null,
    ) {}
}
