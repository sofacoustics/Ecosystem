<?php

namespace App\Data;

use \App\Data\RORNameData;

use Livewire\Wireable;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Concerns\WireableData;

class RORItemData extends Data implements Wireable
{
    use WireableData;

	public $id;
	/** @var \App\Data\RORNameData[] */
	public array $names;
    public function __construct(
      //
    ) {}
}
