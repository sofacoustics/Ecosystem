<?php

namespace App\Livewire;

use Livewire\Component;

use App\Models\SubjectArea;

class SubjectAreaForm extends Component
{
	public $subjectareaable;
	public $subjectareaable_id;
	public $subjectareaable_type;
	public $subjectarea;
	public $controlledSubjectAreaIndex;
	public $additionalSubjectArea;
	public $base_id; 

	protected $rules = [
		'controlledSubjectAreaIndex' => 'required',
	];

	public function mount($subjectareaable, $subjectarea = null)
	{
		$base_id = (\App\Models\Metadataschema::where('name', 'subjectArea')->first()->id);

		$this->subjectareaable = $subjectareaable;
		$this->base_id = $base_id;
		if($subjectarea)
		{
			$this->subjectarea = $subjectarea;
			$this->subjectareaable_id = $subjectarea->subjectareaable_id;
			$this->subjectareaable_type = $subjectarea->subjectareaable_type;
			$this->controlledSubjectAreaIndex = $subjectarea->controlledSubjectAreaIndex - $base_id;
			$this->additionalSubjectArea = $subjectarea->additionalSubjectArea;
		}
		else
		{
			$this->subjectareaable_id = $subjectareaable->id;
			$this->subjectareaable_type = get_class($subjectareaable);
		}
	}

	public function save()
	{
		$this->validate();

		$isNew = !$this->subjectarea;

		if($isNew)
		{
			$this->subjectarea = new SubjectArea();
		}
		$this->subjectarea->subjectareaable_id = $this->subjectareaable_id;
		$this->subjectarea->subjectareaable_type = $this->subjectareaable_type;
		if ($this->controlledSubjectAreaIndex == null)
		{		// Other if not defined
			$this->subjectarea->controlledSubjectAreaIndex = (\App\Models\Metadataschema::where('name', 'subjectArea')->where('value', 'OTHER')->first()->id); 
		}
		else
		{	 
			$this->subjectarea->controlledSubjectAreaIndex = $this->controlledSubjectAreaIndex + ($this->base_id); 
		}
		$this->subjectarea->additionalSubjectArea = $this->additionalSubjectArea;

		$this->subjectarea->save();

    session()->flash('message', $isNew ? 'subject area created successfully.' : 'subject area updated successfully.');

		if($this->subjectareaable_type === 'App\Models\Database')
			return redirect()->route('databases.subjectareas',[ 'database' => $this->subjectareaable->id ]);
		else
			return redirect()->route('tools.subjectareas',[ 'tool' => $this->subjectareaable->id ]);
		
	}

	public function render()
	{
		return view('livewire.subject-area-form');
	}
}
