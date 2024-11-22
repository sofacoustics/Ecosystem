<?php

namespace App\Data;

use Livewire\Wireable;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Concerns\WireableData;

/*
 * Base class for all RADAR Data classes
 */
class RadarData extends Data implements Wireable
{
    use WireableData;
}
