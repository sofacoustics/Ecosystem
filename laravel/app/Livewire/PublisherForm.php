<?php

namespace App\Livewire;

use Livewire\Component;

use App\Models\Publisher;

class PublisherForm extends Component
{
	public $publisherable;
	public $publisherable_id;
	public $publisherable_type;
	public $publisher;
	public $publisherName;
	public $nameIdentifier;
	public $nameIdentifierSchemeIndex;
	public $schemeURI;


	protected $rules = [
		'publisherName' => ['required','max:255'],
		'nameIdentifier' => 'max:255',
		'schemeURI' => 'max:255',
	];

	protected $messages = [
		'publisherName.required' => 'A name is required.',
		'publisherName.max' => 'The publisher name can be only up to 255 characters.',
		'nameIdentifier.max' => 'The name identifier can be only up to 255 characters.',
		'schemeURI.max' => 'The scheme URL can be only up to 255 characters.',
	];

	public function mount($publisherable, $publisher = null)
	{
		$this->publisherable = $publisherable;
		if($publisher)
		{
			$this->publisher = $publisher;
			$this->publisherable_id = $publisher->publisherable_id;
			$this->publisherable_type = $publisher->publisherable_type;
			$this->publisherName = $publisher->publisherName;
			$this->nameIdentifier = $publisher->nameIdentifier;
			$this->nameIdentifierSchemeIndex = $publisher->nameIdentifierSchemeIndex;
			$this->schemeURI = $publisher->schemeURI;
		}
		else
		{
			$this->publisherable_id = $publisherable->id;
			$this->publisherable_type = get_class($publisherable);
		}
	}

	public function save()
	{
		$this->validate();

		$isNew = !$this->publisher;

		if($isNew)
		{
			$this->publisher = new Publisher();
		}
		
		$this->publisher->publisherable_id = $this->publisherable_id;
		$this->publisher->publisherable_type = $this->publisherable_type;
		$this->publisher->publisherName = $this->publisherName;
		$this->publisher->nameIdentifier = $this->nameIdentifier;
		if (empty($this->nameIdentifierSchemeIndex) and !empty($this->nameIdentifier))
		{	 $this->publisher->nameIdentifierSchemeIndex = 0; }
		else
		{	 $this->publisher->nameIdentifierSchemeIndex = $this->nameIdentifierSchemeIndex; }
		$this->publisher->schemeURI = $this->schemeURI;

		$this->publisher->save();

    session()->flash('message', $isNew ? 'publisher created successfully.' : 'publisher updated successfully.');

		if($this->publisherable_type === 'App\Models\Database')
		{
			\App\Models\Database::find($this->publisher->publisherable_id)->touch();
			return redirect()->route('databases.publishers',[ 'database' => $this->publisherable->id ]);
		}
		else
		{
			\App\Models\Tool::find($this->publisher->publisherable_id)->touch();
			return redirect()->route('tools.publishers',[ 'tool' => $this->publisherable->id ]);
		}
	}

	public function render()
	{
			return view('livewire.publisher-form');
	}
}