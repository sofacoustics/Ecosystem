<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class RadardatasetpublisherData extends RadarData
{
    // mandatory
    public string $value;
    public string $nameIdentifierScheme;
    // optional
    public ?string $schemeURI;
    public ?string $nameIdentifier;

    public function __construct(
      //
    ) {}

    public function setNameIdentifierScheme($scheme)
    {
        dd("ok");
    }
}
