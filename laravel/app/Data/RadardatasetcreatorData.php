<?php

namespace App\Data;

use Livewire\Wireable;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Concerns\WireableData;

use App\Data\RadardatasetnameidentifierData;

class RadardatasetcreatorData extends Data implements Wireable
{
    use WireableData;

    public string $givenName,
    public string $familyName,
    public ?RadardatasetnameidentifierData $nameIdentifier = null,
    public ?string $creatorAffiliation = null,

    public function __construct(
      //
    ) {}
}
