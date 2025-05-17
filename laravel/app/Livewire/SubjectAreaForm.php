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


	protected $rules = [
		'controlledSubjectAreaIndex' => 'required',
	];

	public function mount($subjectareaable, $subjectarea = null)
	{
		$this->subjectareaable = $subjectareaable;
		if($subjectarea)
		{
			$this->subjectarea = $subjectarea;
			$this->subjectareaable_id = $subjectarea->subjectareaable_id;
			$this->subjectareaable_type = $subjectarea->subjectareaable_type;
			$this->controlledSubjectAreaIndex = $subjectarea->controlledSubjectAreaIndex-15;
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
		if (empty($this->controlledSubjectAreaIndex))
		{	 $this->subjectarea->controlledSubjectAreaIndex = 46; } // 46 = Other in RADAR 9.1
		else
		{	 $this->subjectarea->controlledSubjectAreaIndex = $this->controlledSubjectAreaIndex+15; }
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
