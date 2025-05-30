<?php

namespace App\Livewire;

use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Redirect;

use App\Models\Database;
use App\Models\Datafile;
use App\Models\Dataset;
use App\Models\Datasetdef;

/*
 * Bulk upload of database files.
 */
class DatabasePurge extends Component
{
	use WithFileUploads; // trait necessary for livewire upload

	public Database $database;
	public $datasets;
	public $datasetsCount = 0;

	public function mount(Database $database)
	{
		$this->database = $database->load('datasetdefs','datasets'); // https://laracasts.com/discuss/channels/livewire/livewire-wiremodel-with-model-relationship
		$this->datasetdefIds = $this->database->datasetdefs->pluck('id');
		$this->datasets = $this->database->datasets;
		$this->datasetsCount = count($this->datasets);
	}

	public function render()
	{
		return view('livewire.database-purge');
	}

	public function purgeDatabase()
	{
		$title = $this->database->title;
			// remove all datasets
		foreach($this->datasets as $dataset)
		{
				$dataset->delete();
		}
		$user = \App\Models\User::where('id', $this->database->user_id)->first();
		return Redirect::route('databases.show',[ 'database' => $this->database, 'user' => $user ]);
	}
}
