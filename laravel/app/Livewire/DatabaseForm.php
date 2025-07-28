<?php

namespace App\Livewire;

use App\Models\Database;
use App\Models\SubjectArea;
use App\Models\Publisher;

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
	public $descriptiongeneral;
	public $descriptionabstract;
	public $descriptionmethods;
	public $descriptionremarks;
	public $productionyear;
	public $publicationyear;
	public $language;
	public $datasources;
	public $software;
	public $processing;
	public $relatedinformation;
	public $controlledrights;
	public $additionalrights;

	public $additionaltitletype_base_id; 
	public $controlledrights_other_id;
	
	protected $rules = [
		'title' => 'required',
		'productionyear' => ['required',  // must be provided
												 'regex:/^(\d{4}(?:-\d{4})?|\d{4}-|unknown)$/i' ], // YYYY or YYYY- or YYYY-YYYY or "unknown"
		'controlledrights' => 'required',
		'descriptiongeneral' => 'max:500',
		'descriptionabstract' => 'max:500',
		'descriptionmethods' => 'max:500',
		'descriptionremarks' => 'max:500',
		'additionaltitle' => 'max:255',
		'additionalrights' => 'max:255',
	];

	protected $messages = [
		'productionyear.required' => 'The production year cannot be empty.',
		'productionyear.regex' => 'Production year must be either YYYY or YYYY-YYYY or the string "unknown".',
		'descriptiongeneral.max' => 'The general description can be only up to 500 characters.',
		'descriptionabstract.max' => 'The abstract can be only up to 500 characters.',
		'descriptionmethods.max' => 'The methods can be only up to 500 characters.',
		'descriptionremarks.max' => 'The technical remarks can be only up to 500 characters.',
		'additionaltitle.max' => 'The subtitle can be only up to 255 characters.',
		'additionalrights.max' => 'The custom license name can be only up to 255 characters.',
	];

	public function mount($database = null)
	{
		$additionaltitletype_base_id = (\App\Models\Metadataschema::where('name', 'additionalTitleType')->first()->id);
		$this->additionaltitletype_base_id = $additionaltitletype_base_id;
		$this->controlledrights_other_id = (\App\Models\Metadataschema::where('name', 'controlledRights')->where('value', 'OTHER')->first()->id); 
		
		if($database) 
		{
			$this->database = $database;
			$this->title = $database->title;
			$this->additionaltitle = $database->additionaltitle;
			if ($database->additionaltitletype == null)
				$this->additionaltitletype = null;
			else
				$this->additionaltitletype = $database->additionaltitletype-$additionaltitletype_base_id;
			$this->descriptiongeneral = $database->descriptiongeneral;
			$this->descriptionabstract = $database->descriptionabstract;
			$this->descriptionmethods = $database->descriptionmethods;
			$this->descriptionremarks = $database->descriptionremarks;
			$this->productionyear = $database->productionyear;
			$this->publicationyear = $database->publicationyear;
			$this->language = $database->language;
			$this->datasources = $database->datasources;
			$this->software = $database->software;
			$this->processing = $database->processing;
			$this->relatedinformation = $database->relatedinformation;
			$this->controlledrights = $database->controlledrights;
			$this->additionalrights = $database->additionalrights;
		}
		else
		{
			$this->language = "eng";
			$this->controlledrights = (\App\Models\Metadataschema::where('name', 'controlledRights')->where('value', 'CC_BY_4_0_ATTRIBUTION')->first()->id); // CC BY as default
			$this->additionaltitletype = 0; // Subtitle
			$this->publicationyear = "unknown"; // dummy, will be set by RADAR when Publishing
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
		$this->database->additionaltitletype = (\App\Models\Metadataschema::where('name', 'additionalTitleType')->where('value', 'Subtitle')->first()->id);  // fix to Subtitle
		$this->database->descriptiongeneral = $this->descriptiongeneral;
		$this->database->descriptionabstract = $this->descriptionabstract;
		$this->database->descriptionmethods = $this->descriptionmethods;
		$this->database->descriptionremarks = $this->descriptionremarks;
		$this->database->productionyear = strtolower($this->productionyear);
		$this->database->publicationyear = $this->publicationyear;
		$this->database->language = $this->language;
		$this->database->resourcetype = null; // needs to be fixed to Dataset 
		$this->database->resource = "SONICOM Ecosystem"; // fix
		$this->database->datasources = $this->datasources;
		$this->database->software = $this->software;
		$this->database->processing = $this->processing;
		$this->database->relatedinformation = $this->relatedinformation;
		$this->database->controlledrights = $this->controlledrights;
		$this->database->additionalrights = $this->additionalrights;

		$this->database->save();
		if($isNew)
		{
			$sa = new SubjectArea();
			$sa->subjectareaable_id = $this->database->id; 
			$sa->subjectareaable_type = "App\Models\Database"; 
			$sa->controlledSubjectAreaIndex = (\App\Models\Metadataschema::where('name', 'subjectArea')->where('value', 'LIFE_SCIENCE')->first()->id); // Life Sciences
			$sa->save(); 

			$sa = new SubjectArea(); 
			$sa->subjectareaable_id = $this->database->id; 
			$sa->subjectareaable_type = "App\Models\Database"; 
			$sa->controlledSubjectAreaIndex = (\App\Models\Metadataschema::where('name', 'subjectArea')->where('value', 'OTHER')->first()->id); // Other
			$sa->additionalSubjectArea = "SONICOM Ecosystem"; 
			$sa->save(); 
			
			$pub = new Publisher(); 
			$pub->publisherable_id = $this->database->id;
			$pub->publisherable_type = "App\Models\Database"; 
			$pub->publisherName = "The SONICOM Ecosystem of the Austrian Academy of Sciences"; 
			$pub->nameIdentifier = "03anc3s24"; 
			$pub->nameIdentifierSchemeIndex = 2; // ROR
			$pub->save(); 
		}
		session()->flash('message', $isNew ? 'Database created successfully.' : 'Database updated successfully.');
		return redirect()->route('databases.show', $this->database);
	}

	public function render()
	{
		return view('livewire.database-form');
	}
}
