<?php

namespace App\Livewire;

use App\Models\Database;

use Livewire\Component;

/*
 * https://neon.tech/guides/laravel-livewire-blog#implementing-the-blog-functionality
 */
class DatabaseVisibility extends Component
{
	public $database;
	public $xx;
	public $visible;
	public $doi;
	public $radarstatus;

	protected $rules = [
	];

    public function mount(Database $database)
    {
        if($database) 
        {
            $this->database = $database;
						$this->visible = $database->visible;
						$this->doi = $database->doi;
						if ($database->radarstatus == null)
							$this->radarstatus = 0;
						else
							$this->radarstatus = $database->radarstatus;
        }
				else
				{
						// throw some error here
				}
    }

    public function expose()
    {
				$this->database->visible = true;
				$this->database->save();
				$this->visible = $this->database->visible;
				$this->js('window.location.reload()'); 
    }

    public function hide()
    {
				$this->database->visible = false;
				$this->database->save();
				$this->visible = $this->database->visible;
				$this->js('window.location.reload()'); 
    }

    public function assignDOI()
    {
				$this->database->doi = "testDOI";
				$this->database->save();
				$this->doi = $this->database->doi;
    }

    public function submitToPublish()
    {
				$this->database->radarstatus=1;
				$this->database->save();
				$this->radarstatus = $this->database->radarstatus;
				$this->js('window.location.reload()'); 
    }

    public function approve() // Emulate the curator approving the publication at the Datathek
    {
				$this->database->radarstatus=2;
				$this->database->save();
				$this->radarstatus = $this->database->radarstatus;
    }

    public function resetDOI()
    {
				$this->database->radarstatus=null;
				$this->database->doi = null;
				$this->database->save();
				$this->doi = $this->database->doi;
				$this->radarstatus = $this->database->radarstatus;
				$this->js('window.location.reload()'); 
    }
    public function render()
    {
        return view('livewire.database-visibility');
    }
}
