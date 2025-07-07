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