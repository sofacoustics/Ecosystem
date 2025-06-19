<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;

use App\Models\Tool;
use App\Services\ToolRadarDatasetBridge;

/*
 * Get RADAR status for a tool and add information
 *
 * Sends status messages to Livewire component StatusMessages
 */
class ToolRadarStatus extends Component
{
	public Tool $tool;

	// RADAR properties
	public $id;
	public $state;
	public $doi;
	public $size;

	public $error;

    #[On('radar-status-changed')]
    public function onRadarStatusChanged($content)
    {
		//$this->dispatch('status-message', 'Received \'radar-status-change\' message');
        $this->getStatus();
    }

	public function mount($tool)
	{
		$this->tool = $tool;
		$this->getStatus();
	}

    public function getStatus()
    {
        $radar = new ToolRadarDatasetBridge($this->tool);
        if($radar->read())
        {
            $this->id = $radar->radar_dataset->id;
            $this->state = $radar->radar_dataset->state;
            $this->doi = $radar?->radar_dataset?->descriptiveMetadata?->identifier?->value ?? null;
            $this->size = $radar?->radar_dataset?->technicalMetadata?->size ?? 0;
		}
		else
		{
            $this->id = null;
            $this->state = null;
            $this->doi = null;
            $this->size = null;
		}
    }

    public function render()
    {
        return view('livewire.tool-radar-status');
    }

}
