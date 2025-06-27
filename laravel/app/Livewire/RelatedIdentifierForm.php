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
		'name.required' => 'Input an identifier (e.g., an URL, depends on the identifies type).',
		'name.max' => 'The identifier can be only up to 255 characters.',
		'relatedidentifiertype.required' => 'Select the type of the identifier.',
		'relationtype.required' => 'Select the type of relation.',
		'databaserelation.required' => 'Select the type of relation.',
		'databaserelatedable.required' => 'Select a database',
		'toolrelation.required' => 'Select the type of relation.',
		'toolrelatedable.required' => 'Select a tool or document',
	];

	public function mount($relatedidentifierable, $relatedidentifier = null)
	{
		if($relatedidentifierable->resourcetype == null)
			$reltype = "DATASET";
		else
			$reltype = \App\Models\Metadataschema::value($relatedidentifierable->resourcetype);
		$this->relatedidentifierable = $relatedidentifierable;
		if($relatedidentifier)
		{		// Edit
			$this->relatedidentifier = $relatedidentifier;
			$this->relatedidentifierable_id = $relatedidentifier->relatedidentifierable_id;
			$this->relatedidentifierable_type = $relatedidentifier->relatedidentifierable_type;
			$isInternal = \App\Models\RelatedIdentifier::isInternalLink($relatedidentifier->name);
			switch($isInternal)
			{	
				case 1: // our database/tool has been linked to a database
					$this->activeTab = 'database'; 
					$this->databaserelation = $relatedidentifier->relationtype;
					$this->databaserelatedable = substr($relatedidentifier->name, strlen("ECOSYSTEM_DATABASE")+1);
					$this->toolrelation = null;
					$this->toolrelatedable = null;
					$this->name = null;
					$this->relatedidentifiertype = null;
					$this->relationtype = null;
					break;
				case 2: // our database/tool has been linked to a tool
					$this->activeTab = 'tool'; 
					$this->databaserelation = null;
					$this->databaserelatedable = null;
					$this->toolrelation = $relatedidentifier->relationtype;
					$this->toolrelatedable = substr($relatedidentifier->name, strlen("ECOSYSTEM_TOOL")+1);
					$this->name = null;
					$this->relatedidentifiertype = null;
					$this->relationtype = null;
					break;
				default: // we have a general link
					$this->activeTab = 'external'; 
					$this->databaserelation = null;
					$this->databaserelatedable = null;
					$this->toolrelation = null;
					$this->toolrelatedable = null;
					$this->name = $relatedidentifier->name;
					$this->relatedidentifiertype = $relatedidentifier->relatedidentifiertype;
					$this->relationtype = $relatedidentifier->relationtype;
			}
		}
		else
		{		// New
			$this->relatedidentifierable_id = $relatedidentifierable->id;
			$this->relatedidentifierable_type = get_class($relatedidentifierable);
			$this->relationtype = \App\Models\Metadataschema::where('name', 'relationType')->where('value', 'IS_DESCRIBED_BY')->first()->id; 
			$this->relatedidentifiertype = (\App\Models\Metadataschema::where('name', 'relatedIdentifierType')->where('value', 'URL')->first()->id); 
			$this->activeTab = 'database';
			if($reltype=="TEXT")
			{	// default for Documents
				$this->databaserelation = \App\Models\Metadataschema::where('name', 'relationType')->where('value', 'DESCRIBES')->first()->id;
				$this->toolrelation = \App\Models\Metadataschema::where('name', 'relationType')->where('value', 'DESCRIBES')->first()->id;
			}
			elseif($reltype=="DATASET")
			{		// default for Databases
				$this->databaserelation = \App\Models\Metadataschema::where('name', 'relationType')->where('value', 'IS_NEW_VERSION_OF')->first()->id; 
				$this->toolrelation = -2; // "was created with"
			}
			else
			{		// default for Tools: "was used to create"
				$this->databaserelation = -1; 
				$this->toolrelation = -1;
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

	public function save($method, $rules)
	{
		$this->validate($rules);
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
				$this->relatedidentifier->name = "ECOSYSTEM_DATABASE_".$database->id; // prefix and id
				$this->relatedidentifier->relatedidentifiertype = \App\Models\Metadataschema::where('name', 'relatedIdentifierType')->where('value', 'URL')->first()->id;
				$this->relatedidentifier->relationtype  = $this->databaserelation;
				break;
			case 'tool':
				$tool = \App\Models\Tool::find($this->toolrelatedable);
				$this->relatedidentifier->name = "ECOSYSTEM_TOOL_".$tool->id; // prefix and id
				$this->relatedidentifier->relatedidentifiertype = \App\Models\Metadataschema::where('name', 'relatedIdentifierType')->where('value', 'URL')->first()->id;
				$this->relatedidentifier->relationtype  = $this->toolrelation;
				break;
			case 'external':
				$this->relatedidentifier->name = $this->name;
				$this->relatedidentifier->relatedidentifiertype = $this->relatedidentifiertype; 
				$this->relatedidentifier->relationtype = $this->relationtype; 
				break;
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
			'databaserelation' => 'required',
			'databaserelatedable' => 'required',
		];
		$this->save('database',$rules); 
	}

	public function saveTool()
	{ 
		$rules = [
			'toolrelation' => 'required',
			'toolrelatedable' => 'required',
		];
		$this->save('tool',$rules); 
	}

	public function saveExternal() 
	{ 
		$rules = [
			'name' => ['required','max:255'],
			'relatedidentifiertype' => 'required',
			'relationtype' => 'required',
		];

		$this->save('external', $rules); 
	}

	public function render()
	{
		return view('livewire.related-identifier-form');
	}
}
