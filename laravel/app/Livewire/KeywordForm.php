<?php

namespace App\Livewire;

use Livewire\Component;

use App\Models\Keyword;

class KeywordForm extends Component
{
	public $database;
	public $keyword;
	public $database_id;
	public $keywordName;
	public $keywordSchemeIndex;
	public $schemeURI;
	public $valueURI;
	public $classificationCode;


	protected $rules = [
		'keywordName' => 'required',
	];

	public function mount($database, $keyword = null)
	{
		$this->database = $database;
		if($keyword)
		{
			$this->keyword = $keyword;
			$this->database_id = $keyword->database_id;
			$this->keywordName = $keyword->keywordName;
			$this->keywordSchemeIndex = $keyword->keywordSchemeIndex;
			$this->schemeURI = $keyword->schemeURI;
			$this->valueURI = $keyword->valueURI;
			$this->classificationCode = $keyword->classificationCode;
		}
		else
		{
			$this->database_id = $this->database->id;
		}
	}

	public function save()
	{
		$this->validate();

		$isNew = !$this->keyword;

		if($isNew)
		{
			$this->keyword = new Keyword();
		}
		
		$this->keyword->database_id = $this->database->id;
		if (empty($this->keywordSchemeIndex))
		{	 $this->keyword->keywordSchemeIndex = 0; } // 0 = Other
		else
		{	 $this->keyword->keywordSchemeIndex = $this->keywordSchemeIndex; }
		$this->keyword->keywordName = $this->keywordName;
		$this->keyword->schemeURI = $this->schemeURI;
		$this->keyword->valueURI = $this->valueURI;
		$this->keyword->classificationCode = $this->classificationCode;

		$this->keyword->save();

    session()->flash('message', $isNew ? 'keyword created successfully.' : 'keyword updated successfully.');

    return redirect()->route('databases.keywords', $this->database);
	}

	public function render()
	{
			return view('livewire.keyword-form');
	}
}