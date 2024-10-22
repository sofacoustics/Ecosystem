<?php

namespace App\Data;

use Spatie\LaravelData\Data;

use App\Data\RadardatasetnameidentifierData;

class RadardatasetcreatorData extends Data
{
    public string $givenName,
    public string $familyName,
    public ?RadardatasetnameidentifierData $nameIdentifier = null,
    public ?string $creatorAffiliation = null,

    public function __construct(
      //
    ) {}
}
