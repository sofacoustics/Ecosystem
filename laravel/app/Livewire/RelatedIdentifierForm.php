<?php

namespace App\Livewire;

use Livewire\Component;

use App\Models\RelatedIdentifier;
use App\Models\Database;

class RelatedIdentifierForm extends Component
{
	public $relatedidentifierable;
	public $relatedidentifierable_id;
	public $relatedidentifierable_type;
	public $relatedidentifier;
	public $name;
	public $relatedidentifiertype;
	public $relationtype;
	public $activeTab;
	public $prefix; // dealing with database or tool? 
	public $ecosystemrelation; // how is the database/tool related with tool/database
	public $ecosystemrelatedable; // selected database or tool
	public $ecosystemrelatedable_ids; // options to select a database or tool, just the IDs
	public $ecosystemrelatedable_names; // options to select a database or tool, just the names

	protected $rules = [
		'name' => ['required','max:255'],
		'relatedidentifiertype' => 'required',
		'relationtype' => 'required',
	];

	protected $messages = [
		'name.required' => 'Input an identifier (e.g., URL) for the relation.',
		'name.max' => 'The identifier can be only up to 255 characters.',
		'relatedidentifiertype.required' => 'Select the type of the identifier.',
		'relationtype.required' => 'Select the type of relation.',
	];

	public function mount($relatedidentifierable, $relatedidentifier = null)
	{
		$this->relatedidentifierable = $relatedidentifierable;
		if($relatedidentifier)
		{
			$this->relatedidentifier = $relatedidentifier;
			$this->relatedidentifierable_id = $relatedidentifier->relatedidentifierable_id;
			$this->relatedidentifierable_type = $relatedidentifier->relatedidentifierable_type;
			$this->name = $relatedidentifier->name;
			$this->relatedidentifiertype = $relatedidentifier->relatedidentifiertype;
			$this->relationtype = $relatedidentifier->relationtype;
			$this->activeTab = 'general'; 
			$this->ecosystemrelatedable_id = ""; // put here the retrieval algorithm of the id
		}
		else
		{
			$this->relatedidentifierable_id = $relatedidentifierable->id;
			$this->relatedidentifierable_type = get_class($relatedidentifierable);
			$this->relationtype = (\App\Models\Metadataschema::where('name', 'relationType')->where('value', 'IS_COMPILED_BY')->first()->id); 
			$this->relatedidentifiertype = (\App\Models\Metadataschema::where('name', 'relatedIdentifierType')->where('value', 'URL')->first()->id); 
			$this->activeTab = 'ecosystem'; 
			if(\App\Models\Metadataschema::value($relatedidentifierable->resourcetype)=="TEXT")
				$this->ecosystemrelatedable_id = 2; // default for Documents: "Describes"
			else
				$this->ecosystemrelatedable_id = 4; // default for Tools: "Was Involved of Creation of"
		}
		if($this->relatedidentifierable_type === 'App\Models\Database')
		{
			$this->prefix = "Database";
			$this->ecosystemrelatedable_ids[] = "";
			$this->ecosystemrelatedable_names[] = "Select a Tool or Document...";
			$tools = \App\Models\Tool::all();
			foreach($tools as $tool)
			{
				array_push($this->ecosystemrelatedable_ids, $tool->id);
				array_push($this->ecosystemrelatedable_names, $tool->title." (".$tool->publicationyear.")");
			}
		}
		else
		{
			if(\App\Models\Metadataschema::value($relatedidentifierable->resourcetype)=="TEXT")
				$this->prefix = "Document";
			else
				$this->prefix = "Tool";
			$this->ecosystemrelatedable_ids[] = "";
			$this->ecosystemrelatedable_names[] = "Select a Database...";
			$databases = \App\Models\Database::all();
			foreach($databases as $database)
			{
				array_push($this->ecosystemrelatedable_ids, $database->id);
				array_push($this->ecosystemrelatedable_names, $database->title." (".$database->publicationyear.")");
			}
		}
	}

	public function selectTab($tab)
	{
		$this->activeTab = $tab;
	}


	public function saveGeneral()
	{
		$this->validate();

		$isNew = !$this->relatedidentifier;

		if($isNew)
		{
			$this->relatedidentifier = new RelatedIdentifier();
		}
		$this->relatedidentifier->relatedidentifierable_id = $this->relatedidentifierable_id;
		$this->relatedidentifier->relatedidentifierable_type = $this->relatedidentifierable_type;
		if ($this->relatedidentifiertype == null)
		{		// Other if not defined
			$this->relatedidentifier->relatedidentifiertype = (\App\Models\Metadataschema::where('name', 'relatedIdentifierType')->where('value', 'OTHER')->first()->id); 
		}
		else
		{	 
			$this->relatedidentifier->relatedidentifiertype = $this->relatedidentifiertype; 
		}
		if ($this->relationtype == null)
		{		// Other if not defined
			$this->relatedidentifier->relationtype = (\App\Models\Metadataschema::where('name', 'relationType')->where('value', 'OTHER')->first()->id); 
		}
		else
		{	 
			$this->relatedidentifier->relationtype = $this->relationtype; 
		}
		$this->relatedidentifier->name = $this->name;
		
		$this->relatedidentifier->save();

		session()->flash('message', $isNew ? 'related identifier created successfully.' : 'related identifier updated successfully.');

		if($this->relatedidentifierable_type === 'App\Models\Database')
		{
			\App\Models\Database::find($this->relatedidentifier->relatedidentifierable_id)->touch();
			return redirect()->route('databases.relatedidentifiers',[ 'database' => $this->relatedidentifierable->id ]);
		}
		else
		{
			\App\Models\Tool::find($this->relatedidentifier->relatedidentifierable_id)->touch();
			return redirect()->route('tools.relatedidentifiers',[ 'tool' => $this->relatedidentifierable->id ]);
		}
	}

	public function saveEcosystem()
	{
		$this->validate();

		$isNew = !$this->relatedidentifier;

		if($isNew)
		{
			$this->relatedidentifier = new RelatedIdentifier();
		}
		$this->relatedidentifier->relatedidentifierable_id = $this->relatedidentifierable_id;
		$this->relatedidentifier->relatedidentifierable_type = $this->relatedidentifierable_type;
		if ($this->relatedidentifiertype == null)
		{		// Other if not defined
			$this->relatedidentifier->relatedidentifiertype = (\App\Models\Metadataschema::where('name', 'relatedIdentifierType')->where('value', 'OTHER')->first()->id); 
		}
		else
		{	 
			$this->relatedidentifier->relatedidentifiertype = $this->relatedidentifiertype; 
		}
		if ($this->relationtype == null)
		{		// Other if not defined
			$this->relatedidentifier->relationtype = (\App\Models\Metadataschema::where('name', 'relationType')->where('value', 'OTHER')->first()->id); 
		}
		else
		{
			$this->relatedidentifier->relationtype = $this->relationtype; 
		}
		$this->relatedidentifier->name = $this->name;
		
		$this->relatedidentifier->save();

		session()->flash('message', $isNew ? 'related identifier created successfully.' : 'related identifier updated successfully.');

		if($this->relatedidentifierable_type === 'App\Models\Database')
		{
			\App\Models\Database::find($this->relatedidentifier->relatedidentifierable_id)->touch();
			return redirect()->route('databases.relatedidentifiers',[ 'database' => $this->relatedidentifierable->id ]);
		}
		else
		{
			\App\Models\Tool::find($this->relatedidentifier->relatedidentifierable_id)->touch();
			return redirect()->route('tools.relatedidentifiers',[ 'tool' => $this->relatedidentifierable->id ]);
		}
	}


	public function render()
	{
		return view('livewire.related-identifier-form');
	}
}
