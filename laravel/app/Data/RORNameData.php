<?php

namespace App\Data;

use Livewire\Wireable;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Concerns\WireableData;

class RORNameData extends Data implements Wireable
{
    use WireableData;

	public $value;
	public $lang;

    public function __construct(
      //
    ) {}
}
