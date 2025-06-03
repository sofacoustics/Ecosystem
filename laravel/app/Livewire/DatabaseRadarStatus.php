<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;

use App\Models\Database;
use App\Services\DatasetRadarBridge;

/*
 * Get RADAR status for a database and add information
 *
 * Sends status messages to Livewire component StatusMessages
 */
class DatabaseRadarStatus extends Component
{
	public Database $database;

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

	public function mount($database)
	{
		$this->database = $database;
		$this->getStatus();
	}

    public function getStatus()
    {
        $radar = new DatasetRadarBridge($this->database);
        if($radar->read())
        {
            $this->id = $radar->dataset->id;
            $this->state = $radar->dataset->state;
            $this->doi = $radar?->dataset?->descriptiveMetadata?->identifier?->value ?? null;
            $this->size = $radar?->dataset?->technicalMetadata?->size ?? 0;
        }
		$this->dispatch('status-message', $radar->message);
    }

    public function render()
    {
        return view('livewire.database-radar-status');
    }

}
