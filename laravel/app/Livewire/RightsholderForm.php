<?php

namespace App\Livewire;

use Livewire\Component;

use App\Models\Rightsholder;

class RightsholderForm extends Component
{
	public $rightsholderable;
	public $rightsholderable_id;
	public $rightsholderable_type;
	public $rightsholder;
	public $rightsholderName;
	public $nameIdentifier;
	public $nameIdentifierSchemeIndex;
	public $schemeURI;


	protected $rules = [
		'rightsholderName' => 'required',
	];

	public function mount($rightsholderable, $rightsholder = null)
	{
		$this->rightsholderable = $rightsholderable;
		if($rightsholder)
		{
			$this->rightsholder = $rightsholder;
			$this->rightsholderable_id = $rightsholder->rightsholderable_id;
			$this->rightsholderable_type = $rightsholder->rightsholderable_type;
			$this->rightsholderName = $rightsholder->rightsholderName;
			$this->nameIdentifier = $rightsholder->nameIdentifier;
			$this->nameIdentifierSchemeIndex = $rightsholder->nameIdentifierSchemeIndex;
			$this->schemeURI = $rightsholder->schemeURI;
		}
		else
		{
			$this->rightsholderable_id = $rightsholderable->id;
			$this->rightsholderable_type = get_class($rightsholderable);
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
		
		$this->rightsholder->rightsholderable_id = $this->rightsholderable_id;
		$this->rightsholder->rightsholderable_type = $this->rightsholderable_type;
		$this->rightsholder->rightsholderName = $this->rightsholderName;
		$this->rightsholder->nameIdentifier = $this->nameIdentifier;
		if (empty($this->nameIdentifierSchemeIndex) and !empty($this->nameIdentifier))
		{	 $this->rightsholder->nameIdentifierSchemeIndex = 0; }
		else
		{	 $this->rightsholder->nameIdentifierSchemeIndex = $this->nameIdentifierSchemeIndex; }
		$this->rightsholder->schemeURI = $this->schemeURI;

		$this->rightsholder->save();

    session()->flash('message', $isNew ? 'rightsholder created successfully.' : 'rightsholder updated successfully.');

		if($this->rightsholderable_type === 'App\Models\Database')
			return redirect()->route('databases.rightsholders',[ 'database' => $this->rightsholderable ]);
		else
			return redirect()->route('tools.rightsholders',[ 'tool' => $this->rightsholderable ]);
	}

	public function render()
	{
			return view('livewire.rightsholder-form');
	}
}