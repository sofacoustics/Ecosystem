<?php

namespace App\Data;

use Livewire\Wireable;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Concerns\WireableData;

/*
 * Base class for all RADAR Data classes
 *
 * Use this if you want to use Livewire!
 * If you don't, you may get a "property type not supported in livewire for property" error.
 */
class RadarData extends Data implements Wireable
{
    use WireableData;
}
