<?php

namespace App\Livewire;

use Livewire\Component;

use App\Models\SubjectArea;

class SubjectAreaForm extends Component
{
	public $database;
	public $subjectarea;
	public $database_id;
	public $controlledSubjectAreaIndex;
	public $additionalSubjectArea;


	protected $rules = [
		'controlledSubjectAreaIndex' => 'required',
	];

	public function mount($database, $subjectarea = null)
	{
		$this->database = $database;
		if($subjectarea)
		{
			$this->subjectarea = $subjectarea;
			$this->database_id = $subjectarea->database_id;
			$this->controlledSubjectAreaIndex = $subjectarea->controlledSubjectAreaIndex-15;
			$this->additionalSubjectArea = $subjectarea->additionalSubjectArea;
		}
		else
		{
			$this->database_id = $this->database->id;
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
		
		$this->subjectarea->database_id = $this->database->id;
		if (empty($this->controlledSubjectAreaIndex))
		{	 $this->subjectarea->controlledSubjectAreaIndex = 46; } // 46 = Other in RADAR 9.1
		else
		{	 $this->subjectarea->controlledSubjectAreaIndex = $this->controlledSubjectAreaIndex+15; }
		$this->subjectarea->additionalSubjectArea = $this->additionalSubjectArea;

		$this->subjectarea->save();

    session()->flash('message', $isNew ? 'subject area created successfully.' : 'subject area updated successfully.');

    return redirect()->route('databases.subjectareas', $this->database);
	}

	public function render()
	{
			return view('livewire.subjectarea-form');
	}
}