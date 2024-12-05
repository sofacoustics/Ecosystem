<?php

namespace App\Data;

use Spatie\LaravelData\Data;

use App\Data\RadardatasetnameidentifierData;
use App\Data\RadardatasetcreatoraffiliationData;

class RadardatasetcreatorData extends RadarData
{
    // mandatory
    public string $creatorName;
    // optional
    public ?string $givenName;
    public ?string $familyName;
    public ?RadardatasetnameidentifierData $nameIdentifier = null;
    public ?RadardatasetcreatoraffiliationData $creatorAffiliation = null;

    public function __construct(
      //
    )
    {
        $creatorName = "";
        $givenName = "";
        $familyName = "";
    }
}
