<?php

namespace App\Livewire;

use Livewire\Component;

use App\Models\Rightsholder;

class RightsholderForm extends Component
{
	public $database;
	public $rightsholder;
	public $database_id;
	public $rightsholderName;
	public $nameIdentifier;
	public $nameIdentifierSchemeIndex;
	public $schemeURI;


	protected $rules = [
		'rightsholderName' => 'required',
	];

	public function mount($database, $rightsholder = null)
	{
		$this->database = $database;
		if($rightsholder)
		{
			$this->rightsholder = $rightsholder;
			$this->database_id = $rightsholder->database_id;
			$this->rightsholderName = $rightsholder->rightsholderName;
			$this->nameIdentifier = $rightsholder->nameIdentifier;
			$this->nameIdentifierSchemeIndex = $rightsholder->nameIdentifierSchemeIndex;
			$this->schemeURI = $rightsholder->schemeURI;
		}
		else
		{
			$this->database_id = $this->database->id;
		}
	}

	public function save()
	{
		$this->validate();

		$isNew = !$this->rightsholder;

		if($isNew)
		{
			$this->rightsholder = new Rightsholder();
		}
		
		$this->rightsholder->database_id = $this->database->id;
		$this->rightsholder->rightsholderName = $this->rightsholderName;
		$this->rightsholder->nameIdentifier = $this->nameIdentifier;
		if (empty($this->nameIdentifierSchemeIndex) and !empty($this->nameIdentifier))
		{	 $this->rightsholder->nameIdentifierSchemeIndex = 0; }
		else
		{	 $this->rightsholder->nameIdentifierSchemeIndex = $this->nameIdentifierSchemeIndex; }
		$this->rightsholder->schemeURI = $this->schemeURI;

		$this->rightsholder->save();

    session()->flash('message', $isNew ? 'rightsholder created successfully.' : 'rightsholder updated successfully.');

    return redirect()->route('databases.rightsholders', $this->database);
	}

	public function render()
	{
			return view('livewire.rightsholder-form');
	}
}