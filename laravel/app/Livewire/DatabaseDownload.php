<?php

namespace App\Livewire;

use Livewire\Attributes\Computed;
use Livewire\Component;

use App\Models\Database;
use App\Models\Datafile;
use App\Models\Dataset;
use App\Models\Datasetdef;

/*
 * Bulk download of database files.
 */
class DatabaseDownload extends Component
{

    public Database $database;

    public function mount(Database $database)
    {
    }

    public function render()
    {
			return view('livewire.database-download');
    }
}
