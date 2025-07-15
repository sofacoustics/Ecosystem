<?php

namespace App\Livewire;

use Livewire\Component;

use App\Models\Creator;

class CreatorForm extends Component
{
	public $creatorable;
	public $creatorable_id;
	public $creatorable_type;
	public $creator;
	public $creatorName;
	public $givenName;
	public $familyName;
	public $nameIdentifier;
	public $nameIdentifierSchemeIndex;
	public $creatorAffiliation;
	public $affiliationIdentifier;
	public $affiliationIdentifierScheme;
	public $option = "person";

	protected $rules = [
/*		'creatorName' => ['required','max:255'],
		'givenName' => 'max:255',
		'familyName' => 'max:255',
		'nameIdentifier' => 'max:255',
		'creatorAffiliation' => 'max:255',
	*/];

	protected $messages = [
		'creatorName.required' => 'A name is required.',
		'creatorName.max' => 'The name can be only up to 255 characters.',
		'givenName.max' => 'The name can be only up to 255 characters.',
		'familyName.max' => 'The name can be only up to 255 characters.',
		'nameIdentifier.max' => 'The name identifier can be only up to 255 characters.',
		'creatorAffiliation.max' => 'The affiliation can be only up to 255 characters.',
	];

	public function mount($creatorable, $creator = null)
	{
		$this->creatorable = $creatorable;
		if($creator)
		{
			$this->creator = $creator;
			$this->creatorable_id = $creator->creatorable_id;
			$this->creatorable_type = $creator->creatorable_type;
			$this->nameIdentifierSchemeIndex = $creator->nameIdentifierSchemeIndex;
			if($creator->familyName)
			{		// Person
				$this->option = "person";
				$this->givenName = $creator->givenName;
				$this->familyName = $creator->familyName;
				$this->nameIdentifier = $creator->nameIdentifier;
				$this->creatorAffiliation = $creator->creatorAffiliation;
				$this->affiliationIdentifier = $creator->affiliationIdentifier;
				$this->affiliationIdentifierScheme = $creator->affiliationIdentifierScheme;
			}
			else
			{		// Institution
				$this->option = "institution";
				$this->givenName = null;
				$this->familyName = null;
				$this->nameIdentifier = null;
				$this->creatorAffiliation = $creator->creatorName;
				$this->affiliationIdentifier = $creator->nameIdentifier;
				$this->affiliationIdentifierScheme = $creator->nameIdentifierSchemeIndex;
			}
		}
		else
		{
			$this->creatorable_id = $creatorable->id;
			$this->creatorable_type = get_class($creatorable);
		}
	}

	public function fillinmydata()
	{
		$names = explode(" ", auth()->user()->name, 2);
		if(count($names)==2)
		{
			$this->givenName = $names[0];
			$this->familyName = $names[1];
		}
		else
			$this->familyName = $names[0];
		$this->nameIdentifier = auth()->user()->orcid;
		$this->nameIdentifierSchemeIndex = 1; // ORCID
	}

	public function save()
	{
		//$this->validate();

		$isNew = !$this->creator;
		if($isNew)
			$this->creator = new Creator();
		
		$this->creator->creatorable_id = $this->creatorable_id;
		$this->creator->creatorable_type = $this->creatorable_type;

		switch($this->option)
		{
			case "person":
				if($this->givenName)
					$this->creator->creatorName = $this->familyName.", ".$this->givenName;
				else
					$this->creator->creatorName = $this->familyName;
				$this->creator->givenName = $this->givenName;
				$this->creator->familyName = $this->familyName;
				$this->creator->nameIdentifier = $this->nameIdentifier;
				if($this->nameIdentifier)
					$this->creator->nameIdentifierSchemeIndex = 1; // ORCID
				else
					$this->creator->nameIdentifierSchemeIndex = null;
				$this->creator->creatorAffiliation = $this->creatorAffiliation;
				if($this->creatorAffiliation)
				{
					$this->creator->affiliationIdentifier = $this->affiliationIdentifier;
					if($this->affiliationIdentifier)
						$this->creator->affiliationIdentifierScheme = 2; // ROR
					else
						$this->creator->affiliationIdentifierScheme = null;
				}
				break;
				
			case "institution":
				$this->creator->creatorName = $this->creatorAffiliation;
				$this->creator->givenName = null;
				$this->creator->familyName = null;
				$this->creator->nameIdentifier = $this->affiliationIdentifier;
				if($this->affiliationIdentifier)
					$this->creator->nameIdentifierSchemeIndex = 2; // ROR
				else
					$this->creator->nameIdentifierSchemeIndex = null;
				$this->creator->creatorAffiliation = null; 
				$this->creator->affiliationIdentifierScheme = null; 
				break;
		}
		
		$this->creator->save();
    session()->flash('message', $isNew ? 'creator created successfully.' : 'creator updated successfully.');

		if($this->creatorable_type === 'App\Models\Database')
		{	
			\App\Models\Database::find($this->creator->creatorable_id)->touch();
			return redirect()->route('databases.creators',[ 'database' => $this->creatorable ]);
		}
		else
		{
			\App\Models\Tool::find($this->creator->creatorable_id)->touch();
			return redirect()->route('tools.creators',[ 'tool' => $this->creatorable ]);
		}
	}

	public function render()
	{
			return view('livewire.creator-form');
	}
}