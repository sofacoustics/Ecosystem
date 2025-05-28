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
	public $datasources;
	public $software;
	public $processing;
	public $relatedinformation;
	public $controlledrights;
	public $additionalrights;

	protected $rules = [
		'title' => 'required',
		'productionyear' => ['required',  // must be provided
												 'regex:/^(\d{4}(?:-\d{4})?|unknown)$/i' ], // YYYY or YYYY-YYYY or "unknown"
		'publicationyear' => 'required',
		'controlledrights' => 'required',
	];

	protected $messages = [
		'productionyear.required' => 'The production year cannot be empty.',
		'productionyear.regex' => 'Production year must be either YYYY or YYYY-YYYY or the string "unknown".',
	];

    public function mount($database = null)
    {
        if($database) 
        {
            $this->database = $database;
            $this->title = $database->title;
						$this->additionaltitle = $database->additionaltitle;
						if ($database->additionaltitletype == null)
							$this->additionaltitletype = null;
						else
							$this->additionaltitletype = $database->additionaltitletype-72;
            $this->description = $database->description;
						if ($database->descriptiontype == null)
							$this->descriptiontype = null;
						else
							$this->descriptiontype = $database->descriptiontype-76;
						$this->productionyear = $database->productionyear;
						$this->publicationyear = $database->publicationyear;
						$this->language = $database->language;
						$this->datasources = $database->datasources;
						$this->software = $database->software;
						$this->processing = $database->processing;
						$this->relatedinformation = $database->relatedinformation;
						$this->controlledrights = $database->controlledrights-47;
						$this->additionalrights = $database->additionalrights;
        }
				else
				{
					$this->language = "eng"; 
					$this->controlledrights = 0; // CC BY
					$this->additionaltitletype = 0; // Subtitle
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
        $this->database->productionyear = strtolower($this->productionyear);
        $this->database->publicationyear = $this->publicationyear;
        $this->database->language = $this->language;
        $this->database->resourcetype = 3; // fix to Dataset 
        $this->database->resource = "SONICOM Ecosystem"; // fix 
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
