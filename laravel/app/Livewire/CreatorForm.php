<?php

namespace App\Livewire;

use Livewire\Component;

use App\Models\Creator;

class CreatorForm extends Component
{
	public $database;
	public $creator;
	public $database_id;
	public $creatorName;
	public $givenName;
	public $familyName;
	public $nameIdentifier;
	public $nameIdentifierScheme;
	public $creatorAffiliation;
	public $affiliationIdentifier;
	public $affiliationIdentifierScheme;


	protected $rules = [
		'creatorName' => 'required',
	];

	public function mount($database, $creator = null)
	{
		$this->database = $database;
		if($creator)
		{
			$this->creator = $creator;
			$this->database_id = $creator->database_id;
			$this->creatorName = $creator->creatorName;
			$this->givenName = $creator->givenName;
			$this->familyName = $creator->familyName;
			$this->nameIdentifier = $creator->nameIdentifier;
			$this->nameIdentifierScheme = $creator->nameIdentifierScheme;
			$this->creatorAffiliation = $creator->creatorAffiliation;
			$this->affiliationIdentifier = $creator->affiliationIdentifier;
			$this->affiliationIdentifierScheme = $creator->affiliationIdentifierScheme;
		}
		else
		{
			$this->database_id = $this->database->id;
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
		
		$this->creator->database_id = $this->database->id;
		$this->creator->creatorName = $this->creatorName;
		$this->creator->givenName = $this->givenName;
		$this->creator->familyName = $this->familyName;
		$this->creator->nameIdentifier = $this->nameIdentifier;
		$this->creator->nameIdentifierScheme = $this->nameIdentifierScheme;
		$this->creator->creatorAffiliation = $this->creatorAffiliation;
		$this->creator->affiliationIdentifier = $this->affiliationIdentifier;
		$this->creator->affiliationIdentifierScheme = $this->affiliationIdentifierScheme;

		$this->creator->save();

    session()->flash('message', $isNew ? 'creator created successfully.' : 'creator updated successfully.');

    return redirect()->route('databases.creators', $this->database);
	}

	public function render()
	{
			return view('livewire.creator-form');
	}
}
