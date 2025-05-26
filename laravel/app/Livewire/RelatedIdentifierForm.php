<?php

namespace App\Livewire;

use Livewire\Component;

use App\Models\RelatedIdentifier;
use App\Models\Database;

class RelatedIdentifierForm extends Component
{
	public $relatedidentifierable;
	public $relatedidentifierable_id;
	public $relatedidentifierable_type;
	public $relatedidentifier;
	public $name;
	public $relatedidentifiertype;
	public $relatedidentifier_base_id; 
	public $relationtype;
	public $relation_base_id; 

	protected $rules = [
		'relatedidentifiertype' => 'required',
		'relationtype' => 'required',
	];

	public function mount($relatedidentifierable, $relatedidentifier = null)
	{
		$relatedidentifier_base_id = (\App\Models\Radar\Metadataschema::where('name', 'relatedIdentifierType')->first()->id);
		$relation_base_id = (\App\Models\Radar\Metadataschema::where('name', 'relationType')->first()->id);

		$this->relatedidentifierable = $relatedidentifierable;
		$this->relatedidentifier_base_id = $relatedidentifier_base_id;
		$this->relation_base_id = $relation_base_id;
		if($relatedidentifier)
		{
			$this->relatedidentifier = $relatedidentifier;
			$this->relatedidentifierable_id = $relatedidentifier->relatedidentifierable_id;
			$this->relatedidentifierable_type = $relatedidentifier->relatedidentifierable_type;
			$this->name = $relatedidentifier->name;
			$this->relatedidentifiertype = $relatedidentifier->relatedidentifiertype - $relatedidentifier_base_id;
			$this->relationtype = $relatedidentifier->relationtype - $relation_base_id;
		}
		else
		{
			$this->relatedidentifierable_id = $relatedidentifierable->id;
			$this->relatedidentifierable_type = get_class($relatedidentifierable);
		}
	}

	public function save()
	{
		$this->validate();

		$isNew = !$this->relatedidentifier;

		if($isNew)
		{
			$this->relatedidentifier = new RelatedIdentifier();
		}
		$this->relatedidentifier->relatedidentifierable_id = $this->relatedidentifierable_id;
		$this->relatedidentifier->relatedidentifierable_type = $this->relatedidentifierable_type;
		if ($this->relatedidentifiertype == null)
		{		// Other if not defined
			$this->relatedidentifier->relatedidentifiertype = (\App\Models\Radar\Metadataschema::where('name', 'relatedIdentifierType')->where('value', 'OTHER')->first()->id); 
		}
		else
		{	 
			$this->relatedidentifier->relatedidentifiertype = $this->relatedidentifiertype + ($this->relatedidentifier_base_id); 
		}
		if ($this->relationtype == null)
		{		// Other if not defined
			$this->relatedidentifier->relationtype = (\App\Models\Radar\Metadataschema::where('name', 'relationType')->where('value', 'OTHER')->first()->id); 
		}
		else
		{	 
			$this->relatedidentifier->relationtype = $this->relationtype + ($this->relation_base_id); 
		}
		$this->relatedidentifier->name = $this->name;
		
		$this->relatedidentifier->save();

    session()->flash('message', $isNew ? 'subject area created successfully.' : 'subject area updated successfully.');

		if($this->relatedidentifierable_type === 'App\Models\Database')
			return redirect()->route('databases.relatedidentifiers',[ 'database' => $this->relatedidentifierable->id ]);
		else
			return redirect()->route('tools.relatedidentifiers',[ 'tool' => $this->relatedidentifierable->id ]);
		
	}

	public function render()
	{
		return view('livewire.related-identifier-form');
	}
}
