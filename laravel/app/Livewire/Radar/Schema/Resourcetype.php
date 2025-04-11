<?php

namespace App\Livewire\Radar\Schema;

use Livewire\Component;

use App\Models\Radar\Metadataschema;

class Resourcetype extends Component
{
    public $resourcetypes;
    public $resourcetype;


    public function mount()
    {
        $this->resourcetypes = \App\Models\Radar\Metadataschema::where('name','resourceType')->get();

        //dd($resourceTypes);
    }

    public function render()
    {
        return view('livewire.radar.schema.resourcetype');
    }
}
