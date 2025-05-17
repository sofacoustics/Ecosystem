<?php

namespace App\Livewire;

use Livewire\Component;

use App\Models\Keyword;

class KeywordForm extends Component
{
	public $keywordable;
	public $keywordable_id;
	public $keywordable_type;
	public $keyword;
	public $keywordName;
	public $keywordSchemeIndex;
	public $schemeURI;
	public $valueURI;
	public $classificationCode;


	protected $rules = [
		'keywordName' => 'required',
	];

	public function mount($keywordable, $keyword = null)
	{
		$this->keywordable = $keywordable;
		if($keyword)
		{
			$this->keyword = $keyword;
			$this->keywordable_id = $keyword->keywordable_id;
			$this->keywordable_type = $keyword->keywordable_type;
			$this->keywordName = $keyword->keywordName;
			$this->keywordSchemeIndex = $keyword->keywordSchemeIndex;
			$this->schemeURI = $keyword->schemeURI;
			$this->valueURI = $keyword->valueURI;
			$this->classificationCode = $keyword->classificationCode;
		}
		else
		{
			$this->keywordable_id = $keywordable->id;
			$this->keywordable_type = get_class($keywordable);
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
		
		$this->keyword->keywordable_id = $this->keywordable_id;
		$this->keyword->keywordable_type = $this->keywordable_type;

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

		if($this->keywordable_type === 'App\Models\Database')
			return redirect()->route('databases.keywords',[ 'database' => $this->keywordable->id ]);
		else
			return redirect()->route('tools.keywords',[ 'tool' => $this->keywordable->id ]);
	}

	public function render()
	{
			return view('livewire.keyword-form');
	}
}