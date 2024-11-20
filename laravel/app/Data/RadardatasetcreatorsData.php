<?php

namespace App\Data;

use Livewire\Wireable;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Concerns\WireableData;

use App\Data\RadardatasetcreatorData;

class RadardatasetcreatorsData extends Data implements Wireable
{
    use WireableData;

    /** @var \App\Data\Radardatasetcreator[] */
    public array $creator;

    public function __construct(
      //
    ) {}
}
