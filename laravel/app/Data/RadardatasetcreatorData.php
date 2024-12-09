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

    /** @var \App\Data\RadardatasetnameidentifierData[] */
    public ?array $nameIdentifier = [];
    public ?RadardatasetcreatoraffiliationData $creatorAffiliation = null;

    public function __construct(
      //
    )
    {
        $creatorName = "";
    }
}
