<?php

namespace App\Data;

use \App\Data\RORItemData;

use Livewire\Wireable;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Concerns\WireableData;

class RORData extends Data implements Wireable
{
    use WireableData;

	public $number_of_results;
	/** @var \App\Data\RORItemData[] */
	public array $items;
    public function __construct(
      //
    ) {}
}


