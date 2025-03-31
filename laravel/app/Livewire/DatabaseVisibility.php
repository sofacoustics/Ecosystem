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
    }

    public function hide()
    {
				$this->database->visible = false;
				$this->database->radarstatus = 0;
				$this->database->save();
				$this->visible = $this->database->visible;
				$this->radarstatus = $this->database->radarstatus;
    }

    public function doi()
    {
				$this->database->doi = 'testDOI';
				$this->database->save();
				$this->doi = $this->database->doi;
    }

    public function publish()
    {
				$this->database->radarstatus++;
				$this->database->save();
				$this->radarstatus = $this->database->radarstatus;
    }




    public function render()
    {
        return view('livewire.database-visibility');
    }
}
