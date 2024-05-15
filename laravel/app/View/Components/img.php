<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

use App\Models\Datafile;

class img extends Component
{
    public string $asset;
    /**
     * Create a new component instance.
     */
    public function __construct(
        public Datafile $datafile,
    //public string $asset,
    )
    {
        if($datafile->datasetdef->datafiletype->name == "asdf")
        {
            if($asset == "")
                $this->asset = $datafile->asset(); 
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.img');
    }
}
