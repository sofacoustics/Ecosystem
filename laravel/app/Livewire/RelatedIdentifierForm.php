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
	public $databaserelation; // what is the relation to a database?
	public $databaserelatedable; // selected database
	public $databaserelatedable_ids; // options to select a database, just the IDs
	public $databaserelatedable_names; // options to select a database, just the names
	public $toolrelation; // what is the relation to a tool?
	public $toolrelatedable; // selected tool
	public $toolrelatedable_ids; // options to select a tool, just the IDs
	public $toolrelatedable_names; // options to select a tool, just the names

	protected $messages = [
		'name.required' => 'Input an identifier (e.g., URL) for the relation.',
		'name.max' => 'The identifier can be only up to 255 characters.',
		'relatedidentifiertype.required' => 'Select the type of the identifier.',
		'relationtype.required' => 'Select the type of relation.',
	];

	public function mount($relatedidentifierable, $relatedidentifier = null)
	{
		if($relatedidentifierable->resourcetype == null)
			$reltype = "DATASET";
		else
			$reltype = \App\Models\Metadataschema::value($relatedidentifierable->resourcetype);
		$this->relatedidentifierable = $relatedidentifierable;
		if($relatedidentifier)
		{
			$this->relatedidentifier = $relatedidentifier;
			$this->relatedidentifierable_id = $relatedidentifier->relatedidentifierable_id;
			$this->relatedidentifierable_type = $relatedidentifier->relatedidentifierable_type;
			$this->name = $relatedidentifier->name;
			$this->relatedidentifiertype = $relatedidentifier->relatedidentifiertype;
			$this->relationtype = $relatedidentifier->relationtype;
			$this->activeTab = 'external'; 
			$this->databaserelation = ""; // put here the retrieval algorithm of the id
			$this->databaserelatedable = ""; // put here the retrieval algorithm of the id
			$this->toolrelation = ""; // put here the retrieval algorithm of the id
			$this->toolrelatedable = ""; // put here the retrieval algorithm of the id
		}
		else
		{
			$this->relatedidentifierable_id = $relatedidentifierable->id;
			$this->relatedidentifierable_type = get_class($relatedidentifierable);
			$this->relationtype = (\App\Models\Metadataschema::where('name', 'relationType')->where('value', 'IS_COMPILED_BY')->first()->id); 
			$this->relatedidentifiertype = (\App\Models\Metadataschema::where('name', 'relatedIdentifierType')->where('value', 'URL')->first()->id); 
			$this->activeTab = 'database';
			if($reltype=="TEXT")
			{
				$this->databaserelation = 2; // default for Documents: "Describes"
				$this->toolrelation = 2; // default for Documents: "Describes"
			}
			elseif($reltype=="DATASET")
			{
				$this->databaserelation = 3; // default for Databases: "Was Created With"
				$this->toolrelation = 3; // default for Databases: "Was Created With"
			}
			else
			{
				$this->databaserelation = 4; // default for Tools: "Was Involved of Creation of"
				$this->toolrelation = 4; // default for Tools: "Was Involved of Creation of"
			}
		}

		if($this->relatedidentifierable_type === 'App\Models\Database')
			$this->prefix = "Database";
		else
		{
			if($reltype=="TEXT")
				$this->prefix = "Document";
			else
				$this->prefix = "Tool";
		}
		$this->toolrelatedable_ids[] = "";
		$this->toolrelatedable_names[] = "... a Tool or Document";
		$tools = \App\Models\Tool::all();
		foreach($tools as $tool)
		{
			array_push($this->toolrelatedable_ids, $tool->id);
			array_push($this->toolrelatedable_names, $tool->title." (".$tool->publicationyear.")");
		}

		$this->databaserelatedable_ids[] = "";
		$this->databaserelatedable_names[] = "... a Database";
		$databases = \App\Models\Database::all();
		foreach($databases as $database)
			if($database->visible)
			{
				array_push($this->databaserelatedable_ids, $database->id);
				array_push($this->databaserelatedable_names, $database->title." (".$database->publicationyear.")");
			}
	}

	public function selectTab($tab)
	{
		$this->activeTab = $tab;
	}


	private function save($method, $rules)
	{
		$this->validate($rules);
		dd($method);

		$isNew = !$this->relatedidentifier;
		if($isNew)
		{
			$this->relatedidentifier = new RelatedIdentifier();
		}
		$this->relatedidentifier->relatedidentifierable_id = $this->relatedidentifierable_id;
		$this->relatedidentifier->relatedidentifierable_type = $this->relatedidentifierable_type;
		switch($method)
		{
			case 'database':
				$database = \App\Models\Database::find($this->databaserelatedable);
				$this->relatedidentifier->name = route('databases.show',[ 'database' => $database->id]); // we store the URL to the database
				$this->relatedidentifier->relatedidentifiertype = \App\Models\Metadataschema::where('name', 'relatedIdentifierType')->where('value', 'URL')->first()->id;
				switch($this->databaserelation)
				{
					case -1:
						$this->relatedidentifier->relationtype  = \App\Models\Metadataschema::where('name', 'relationType')->where('value', 'COMPILES')->first()->id;
					default:
						$this->relatedidentifier->relationtype = $this->databaserelation;
				}
			case 'tool':
				$tool = \App\Models\Tool::find($this->toolrelatedable);
				$this->relatedidentifier->name = route('tools.show',[ 'tool' => $tool->id]); // we store the URL to the tool
				$this->relatedidentifier->relatedidentifiertype = \App\Models\Metadataschema::where('name', 'relatedIdentifierType')->where('value', 'URL')->first()->id;
				switch($this->toolrelation)
				{
					case -1:
						$this->relatedidentifier->relationtype  = \App\Models\Metadataschema::where('name', 'relationType')->where('value', 'COMPILES')->first()->id;
					case -2:
						$this->relatedidentifier->relationtype  = \App\Models\Metadataschema::where('name', 'relationType')->where('value', 'IS_COMPILED_BY')->first()->id;
					case -3:
						$this->relatedidentifier->relationtype  = \App\Models\Metadataschema::where('name', 'relationType')->where('value', 'IS_CONTINUED_BY')->first()->id;
					default:
						$this->relatedidentifier->relationtype = $this->toolrelation;
				}
			case 'external':
				$this->relatedidentifier->name = $this->name;
				$this->relatedidentifier->relatedidentifiertype = $this->relatedidentifiertype; 
				$this->relatedidentifier->relationtype = $this->relationtype; 
		}
		
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

	public function saveDatabase()
	{ 
		$rules = [
			'name' => ['required','max:255'],
			'relatedidentifiertype' => 'required',
			'relationtype' => 'required',
		];

		$this->save('database',$rules); 
	}
	public function saveTool() { save('tool'); }

	public function saveExternal() 
	{ 
		$rules = [
			'name' => ['required','max:255'],
			'relatedidentifiertype' => 'required',
			'relationtype' => 'required',
		];

		$this->save('external'); 
	}
	

/*	public function saveTool()
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

*/


	public function render()
	{
		return view('livewire.related-identifier-form');
	}
}
