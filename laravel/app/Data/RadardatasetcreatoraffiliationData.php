<?php

namespace App\Data;

use Livewire\Wireable;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Concerns\WireableData;

class RadardatasetcreatoraffiliationData extends RadarData implements Wireable
{
    use WireableData;

    public string $value;
    public string $schemeURI;
    public string $affiliationIdentifier;
    public string $affiliationIdentifierScheme;

    public function __construct(
      //
    ) {}
}
