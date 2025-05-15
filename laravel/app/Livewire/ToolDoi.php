<?php

namespace App\Livewire;

use App\Models\Tool;

use Livewire\Component;

class ToolDoi extends Component
{
	public $tool;
	public $xx;
	public $doi;
	public $radarstatus;

	protected $rules = [
	];

    public function mount(Tool $tool)
    {
        if($tool) 
        {
            $this->tool = $tool;
						$this->doi = $tool->doi;
						if ($tool->radarstatus == null)
							$this->radarstatus = 0;
						else
							$this->radarstatus = $tool->radarstatus;
        }
				else
				{
						// throw some error here
				}
    }

    public function assignDOI()
    {
				$this->tool->doi = "testDOI";
				$this->tool->save();
				$this->doi = $this->tool->doi;
    }

    public function submitToPublish()
    {
				$this->tool->radarstatus=1;
				$this->tool->save();
				$this->radarstatus = $this->tool->radarstatus;
				$this->js('window.location.reload()'); 
    }

    public function approve() // Emulate the curator approving the publication at the Datathek
    {
				$this->tool->radarstatus=2;
				$this->tool->save();
				$this->radarstatus = $this->tool->radarstatus;
    }

    public function resetDOI()
    {
				$this->tool->radarstatus=null;
				$this->tool->doi = null;
				$this->tool->save();
				$this->doi = $this->tool->doi;
				$this->radarstatus = $this->tool->radarstatus;
				$this->js('window.location.reload()'); 
    }
    public function render()
    {
        return view('livewire.tool-doi');
    }
}
