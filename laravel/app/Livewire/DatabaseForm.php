<?php

namespace App\Livewire;

use App\Models\Database;

use Livewire\Component;

/*
 * https://neon.tech/guides/laravel-livewire-blog#implementing-the-blog-functionality
 */
class DatabaseForm extends Component
{
    public $database;
    public $title;
    public $additionaltitle;
    public $additionaltitletype;
    public $description;
		public $descriptiontype;
    public $productionyear;
    public $publicationyear;
    public $language;
    public $resourcetype;
    public $resource;
    public $datasources;
    public $software;
    public $processing;
    public $relatedinformation;
		public $controlledrights;
    public $additionalrights;

    //jw:todo rules
	protected $rules = [
		'title' => 'required',
		'productionyear' => 'required',
		'publicationyear' => 'required',
		'resourcetype' => 'required',
		'controlledrights' => 'required',
	];

    public function mount($database = null)
    {
        if($database) 
        {
            $this->database = $database;
            $this->title = $database->title;
						$this->additionaltitle = $database->additionaltitle;
						$this->additionaltitletype = $database->additionaltitletype-72;
            $this->description = $database->description;
            $this->descriptiontype = $database->descriptiontype-76;
						$this->productionyear = $database->productionyear;
						$this->publicationyear = $database->publicationyear;
						$this->language = $database->language;
						$this->resourcetype = $database->resourcetype-1;
						$this->resource = $database->resource;
						$this->datasources = $database->datasources;
						$this->software = $database->software;
						$this->processing = $database->processing;
						$this->relatedinformation = $database->relatedinformation;
						$this->controlledrights = $database->controlledrights-47;
						$this->additionalrights = $database->additionalrights;
        }
    }

    public function save()
    {

        $this->validate();

        $isNew = !$this->database;

        if($isNew)
        {
            $this->database = new Database();
            $this->database->user_id = auth()->id();
        }

        $this->database->title = $this->title;
        $this->database->additionaltitle = $this->additionaltitle;
				if ($this->additionaltitletype == null) { $this->database->additionaltitletype = null; }
					else { $this->database->additionaltitletype = $this->additionaltitletype+72; }
        $this->database->description = $this->description;
				if ($this->descriptiontype == null) { $this->database->descriptiontype = null; }
					else { $this->database->descriptiontype = $this->descriptiontype+76; }
        $this->database->productionyear = $this->productionyear;
        $this->database->publicationyear = $this->publicationyear;
        $this->database->language = $this->language;
        $this->database->resourcetype = $this->resourcetype+1;
        $this->database->resource = $this->resource;
        $this->database->datasources = $this->datasources;
        $this->database->software = $this->software;
        $this->database->processing = $this->processing;
        $this->database->relatedinformation = $this->relatedinformation;
        $this->database->controlledrights = $this->controlledrights+47;
        $this->database->additionalrights = $this->additionalrights;

        $this->database->save();

        session()->flash('message', $isNew ? 'Database created successfully.' : 'Database updated successfully.');
        return redirect()->route('databases.show', $this->database);
    }

    public function render()
    {
        return view('livewire.database-form');
    }
}
