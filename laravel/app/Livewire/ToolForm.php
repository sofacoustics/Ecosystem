<?php

namespace App\Livewire;

use App\Models\Tool;
use App\Models\SubjectArea;

use Livewire\Component;

class ToolForm extends Component
{
	public $tool;
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
	public $resourcetype;
	public $resource;
	
	public $resourcetype_base_id; 
	public $resourcetype_other_id; 
	
	public $descriptiontype_base_id; 
	public $controlledrights_base_id;
	public $controlledrights_other_id;
	
	protected $rules = [
		'title' => 'required',
		'productionyear' => ['required',  // must be provided
												 'regex:/^(\d{4}(?:-\d{4})?|unknown)$/i' ], // YYYY or YYYY-YYYY or "unknown"
		'publicationyear' => 'required',
		'controlledrights' => 'required',
		'resourcetype' => 'required',
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

	public function mount($tool = null)
	{
		$resourcetype_base_id = (\App\Models\Radar\Metadataschema::where('name', 'resourcetype')->first()->id);
		$this->resourcetype_other_id = (\App\Models\Radar\Metadataschema::where('name', 'resourcetype')->where('value', 'OTHER')->first()->id) - $resourcetype_base_id; 
		$additionaltitletype_base_id = (\App\Models\Radar\Metadataschema::where('name', 'additionalTitleType')->first()->id);
		$controlledrights_base_id = (\App\Models\Radar\Metadataschema::where('name', 'controlledRights')->first()->id);
		$this->controlledrights_other_id = (\App\Models\Radar\Metadataschema::where('name', 'controlledRights')->where('value', 'OTHER')->first()->id) - $controlledrights_base_id; 

		$this->additionaltitletype_base_id = $additionaltitletype_base_id;
		$this->controlledrights_base_id = $controlledrights_base_id; 
		$this->resourcetype_base_id = $resourcetype_base_id;

		if($tool) 
		{
			$this->tool = $tool;
			$this->title = $tool->title;
			$this->additionaltitle = $tool->additionaltitle;
			if ($tool->additionaltitletype == null)
				$this->additionaltitletype = null;
			else
				$this->additionaltitletype = $tool->additionaltitletype-$additionaltitletype_base_id;
			$this->descriptiongeneral = $tool->descriptiongeneral;
			$this->descriptionabstract = $tool->descriptionabstract;
			$this->descriptionmethods = $tool->descriptionmethods;
			$this->descriptionremarks = $tool->descriptionremarks;
			$this->productionyear = $tool->productionyear;
			$this->publicationyear = $tool->publicationyear;
			$this->language = $tool->language;
			$this->datasources = $tool->datasources;
			$this->software = $tool->software;
			$this->processing = $tool->processing;
			$this->relatedinformation = $tool->relatedinformation;
			$this->controlledrights = $tool->controlledrights-$controlledrights_base_id;
			$this->additionalrights = $tool->additionalrights;
			$this->resourcetype = $tool->resourcetype-$resourcetype_base_id; 
			$this->resource = $tool->resource; 
		}
		else
		{
			$this->language = "eng"; 
			$this->controlledrights = 0; // CC BY
			$this->additionaltitletype = 0; // Subtitle
			$this->publicationyear = "unknown"; // dummy, will be set by RADAR when Publishing
			$this->resourcetype = (\App\Models\Radar\Metadataschema::where('name', 'resourcetype')->where('value', 'SOFTWARE')->first()->id) - $this->resourcetype_base_id;  
		}
	}

	public function save()
	{
		$this->validate();

		$isNew = !$this->tool;

		if($isNew)
		{
				$this->tool = new Tool();
				$this->tool->user_id = auth()->id();
		}

		$this->tool->title = $this->title;
		$this->tool->additionaltitle = $this->additionaltitle;
		$this->tool->additionaltitletype = (\App\Models\Radar\Metadataschema::where('name', 'additionalTitleType')->where('value', 'Subtitle')->first()->id);  // fix to Subtitle
		$this->tool->descriptiongeneral = $this->descriptiongeneral;
		$this->tool->descriptionabstract = $this->descriptionabstract;
		$this->tool->descriptionmethods = $this->descriptionmethods;
		$this->tool->descriptionremarks = $this->descriptionremarks;
		$this->tool->productionyear = strtolower($this->productionyear);
		$this->tool->publicationyear = $this->publicationyear;
		$this->tool->language = $this->language;
		$this->tool->resourcetype = $this->resourcetype + ($this->resourcetype_base_id); 
		$this->tool->resource = $this->resource;
		$this->tool->datasources = $this->datasources;
		$this->tool->software = $this->software;
		$this->tool->processing = $this->processing;
		$this->tool->relatedinformation = $this->relatedinformation;
		$this->tool->controlledrights = $this->controlledrights+$this->controlledrights_base_id;
		$this->tool->additionalrights = $this->additionalrights;

		$this->tool->save();

		if($isNew)
		{
			$sa = new SubjectArea(); 
			$sa->subjectareaable_id = $this->tool->id; 
			$sa->subjectareaable_type = "App\Models\Tool"; 
			$sa->controlledSubjectAreaIndex = (\App\Models\Radar\Metadataschema::where('name', 'subjectArea')->where('value', 'LIFE_SCIENCE')->first()->id); // Life Sciences
			$sa->save(); 

			$sa = new SubjectArea(); 
			$sa->subjectareaable_id = $this->tool->id; 
			$sa->subjectareaable_type = "App\Models\Tool"; 
			$sa->controlledSubjectAreaIndex = (\App\Models\Radar\Metadataschema::where('name', 'subjectArea')->where('value', 'OTHER')->first()->id); // Other
			$sa->additionalSubjectArea = "SONICOM Ecosystem"; 
			$sa->save(); 
		}
		
		session()->flash('message', $isNew ? 'Tool created successfully.' : 'Tool updated successfully.');
		return redirect()->route('tools.show', $this->tool);
	}

	public function render()
	{
		return view('livewire.tool-form');
	}
}
