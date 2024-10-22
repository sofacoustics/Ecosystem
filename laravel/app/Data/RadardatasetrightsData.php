<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class RadardatasetrightsData extends Data
{
    // mandatory
    public string $controlledRights;
    // optional
    public ?string $additionalRights;

    public function __construct(
      //
    ) {}
}
