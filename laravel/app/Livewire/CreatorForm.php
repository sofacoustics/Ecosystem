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

	protected $rules = [
		'creatorName' => ['required','max:255'],
		'givenName' => 'max:255',
		'familyName' => 'max:255',
		'nameIdentifier' => 'max:255',
		'creatorAffiliation' => 'max:255',
	];

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
			$this->creatorName = $creator->creatorName;
			$this->givenName = $creator->givenName;
			$this->familyName = $creator->familyName;
			$this->nameIdentifier = $creator->nameIdentifier;
			$this->nameIdentifierSchemeIndex = $creator->nameIdentifierSchemeIndex;
			$this->creatorAffiliation = $creator->creatorAffiliation;
			$this->affiliationIdentifier = $creator->affiliationIdentifier;
			$this->affiliationIdentifierScheme = $creator->affiliationIdentifierScheme;
		}
		else
		{
			$this->creatorable_id = $creatorable->id;
			$this->creatorable_type = get_class($creatorable);
		}
	}

	public function save()
	{
		$this->validate();

		$isNew = !$this->creator;

		if($isNew)
		{
			$this->creator = new Creator();
		}
		
		$this->creator->creatorable_id = $this->creatorable_id;
		$this->creator->creatorable_type = $this->creatorable_type;

		$this->creator->creatorName = $this->creatorName;
		$this->creator->givenName = $this->givenName;
		$this->creator->familyName = $this->familyName;
		$this->creator->nameIdentifier = $this->nameIdentifier;
		if (empty($this->nameIdentifierSchemeIndex) and !empty($this->nameIdentifier))
		{	 $this->creator->nameIdentifierSchemeIndex = 0; }
		else
		{	 $this->creator->nameIdentifierSchemeIndex = $this->nameIdentifierSchemeIndex; }
		if ($this->creator->nameIdentifierSchemeIndex == '')
			$this->creator->nameIdentifierSchemeIndex = null; 
		$this->creator->creatorAffiliation = $this->creatorAffiliation;
		$this->creator->affiliationIdentifier = $this->affiliationIdentifier;
		$this->creator->affiliationIdentifierScheme = $this->affiliationIdentifierScheme;

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