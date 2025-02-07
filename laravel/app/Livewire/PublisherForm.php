<?php

namespace App\Livewire;

use Livewire\Component;

use App\Models\Publisher;

class PublisherForm extends Component
{
	public $database;
	public $publisher;
	public $database_id;
	public $publisherName;
	public $nameIdentifier;
	public $nameIdentifierSchemeIndex;
	public $schemeURI;


	protected $rules = [
		'publisherName' => 'required',
	];

	public function mount($database, $publisher = null)
	{
		$this->database = $database;
		if($publisher)
		{
			$this->publisher = $publisher;
			$this->database_id = $publisher->database_id;
			$this->publisherName = $publisher->publisherName;
			$this->nameIdentifier = $publisher->nameIdentifier;
			$this->nameIdentifierSchemeIndex = $publisher->nameIdentifierSchemeIndex;
			$this->schemeURI = $publisher->schemeURI;
		}
		else
		{
			$this->database_id = $this->database->id;
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
		
		$this->publisher->database_id = $this->database->id;
		$this->publisher->publisherName = $this->publisherName;
		$this->publisher->nameIdentifier = $this->nameIdentifier;
		if (empty($this->nameIdentifierSchemeIndex) and !empty($this->nameIdentifier))
		{	 $this->publisher->nameIdentifierSchemeIndex = 0; }
		else
		{	 $this->publisher->nameIdentifierSchemeIndex = $this->nameIdentifierSchemeIndex; }
		$this->publisher->schemeURI = $this->schemeURI;

		$this->publisher->save();

    session()->flash('message', $isNew ? 'publisher created successfully.' : 'publisher updated successfully.');

    return redirect()->route('databases.publishers', $this->database);
	}

	public function render()
	{
			return view('livewire.publisher-form');
			return view('livewire.publisher-form');
	}
}