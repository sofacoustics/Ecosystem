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
	public $schemeURIother;

	protected $rules = [
		'rightsholderName' => ['required','max:255'],
		'nameIdentifier' => 'max:255',
		'schemeURIother' => 'max:255',
	];

	protected $messages = [
		'rightsholderName.required' => 'A name is required.',
		'rightsholderName.max' => 'The name can be only up to 255 characters.',
		'nameIdentifier.max' => 'The name can be only up to 255 characters.',
		'schemeURIother.max' => 'The name can be only up to 255 characters.',
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
			$this->schemeURIother = $rightsholder->schemeURI;
		}
		else
		{
			$this->rightsholderable_id = $rightsholderable->id;
			$this->rightsholderable_type = get_class($rightsholderable);
		}
	}

	public function fillinmydata()
	{
		$this->rightsholderName = auth()->user()->name;
		$this->nameIdentifier = auth()->user()->orcid;
		$this->nameIdentifierSchemeIndex = 1; // ORCID
		$this->schemeURIother = null;
	}

	/*
	 * If a link (ORCID, ROR, DOI) has been entered, switch to the corresponding scheme
	 * and remove the URL prefix (called when the input field loses focus).
	 */
	public function updatedNameIdentifier()
	{
		$detected = Rightsholder::detectNameIdentifier($this->nameIdentifier);
		if($detected)
		{
			$this->nameIdentifierSchemeIndex = (string) $detected['scheme']; // string like the <select> values (0 == null in PHP)
			$this->nameIdentifier = $detected['id'];
			if($detected['schemeURI'])
				$this->schemeURIother = $detected['schemeURI'];
		}
	}

	public function save()
	{
		$this->updatedNameIdentifier(); // in case the form was submitted directly after pasting a link
		$this->validate();

		$isNew = !$this->rightsholder;

		if($isNew)
		{
			$this->rightsholder = new Rightsholder();
		}
		
		$this->rightsholder->rightsholderable_id = $this->rightsholderable_id;
		$this->rightsholder->rightsholderable_type = $this->rightsholderable_type;
		$this->rightsholder->rightsholderName = $this->rightsholderName;
		if($this->nameIdentifierSchemeIndex!=null)
		{
			$this->rightsholder->nameIdentifierSchemeIndex = $this->nameIdentifierSchemeIndex;
			$this->rightsholder->nameIdentifier = $this->nameIdentifier;
			$this->rightsholder->schemeURI = $this->schemeURIother;
		}
		else
		{
			$this->rightsholder->nameIdentifierSchemeIndex = null;
			$this->rightsholder->nameIdentifier = null;
			$this->rightsholder->schemeURI = null;
		}

		$this->rightsholder->save();

    session()->flash('message', $isNew ? 'rightsholder created successfully.' : 'rightsholder updated successfully.');

		if($this->rightsholderable_type === 'App\Models\Database')
		{
			\App\Models\Database::find($this->rightsholder->rightsholderable_id)->touch();
			return redirect()->route('databases.rightsholders',[ 'database' => $this->rightsholderable ]);
		}
		else
		{
			\App\Models\Tool::find($this->rightsholder->rightsholderable_id)->touch();
			return redirect()->route('tools.rightsholders',[ 'tool' => $this->rightsholderable ]);
		}
	}

	public function render()
	{
			return view('livewire.rightsholder-form');
	}
}