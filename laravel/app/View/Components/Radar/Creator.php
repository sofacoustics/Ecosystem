<?php

namespace App\View\Components\Radar;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

use \App\Data\RadardatasetcreatorData as CreatorData;
use \App\Models\Radar\Dataset\Creator as CreatorModel;

class Creator extends Component
{
    public CreatorModel $creator; // used for creator logic
    public $class;

    /**
     * Create a new component instance.
     */
    public function __construct(
        public CreatorData $data, // the Data class
        public string $model, // used for wire:model
        public string $index, // useful for deleting this creator
    )
    {
        $this->creator = new CreatorModel($data);
        if($this->creator->type() == "person")
            $this->class = "md:w-1/3";
        else if($this->creator->type() == "institution")
            $this->class = "md:w-1/3";
    }


    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.radar.creator');
    }
}
