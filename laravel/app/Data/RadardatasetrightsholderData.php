<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class RadardatasetrightsholderData extends RadarData
{
    // mandatory
    public string $value;
    public string $nameIdentifierScheme;
    // optional
    public ?string $nameIdentifier;
    public ?string $schemeURI;

    public function __construct(
      //
    ) {}
}
