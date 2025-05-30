<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Validation\Rule;


use App\Models\Datasetdef;
use App\Models\Datafiletype;

class DatasetdefForm extends Component
{
	public $database;
	public $datasetdef;
	public $name;
	public $description;
	public $database_id;
	public $datafiletype_id;
	public $widget_id;

	public $datafiletypes; // get from Datafiletyp
	public $widgets; // get from Widgets

	protected $messages = [
		'name.required' => 'The name cannot be empty.',
		'name.unique' => 'There is already a dataset definition with this name in your database. Choose an other name.',
		'name.not_regex' => 'The name must not contain any of the following characters: <>:&"/\\|?*',
		'name.max' => 'The name can be only up to 255 characters.',
		'description.max' => 'The description can be only up to 255 characters.',
		'datafiletype_id.required' => 'Select the type of the datafile.',
	];

	public function mount($database, $datasetdef = null)
	{
		$this->database = $database;
		if($datasetdef)
		{
			$this->datasetdef = $datasetdef;
			$this->name = $datasetdef->name;
			$this->description = $datasetdef->description;
			$this->database_id = $datasetdef->database_id;
			$this->datafiletype_id = $datasetdef->datafiletype_id;
			$this->widget_id = $datasetdef->widget_id;
		}
		else
		{
			$this->datafiletype_id = null;
			$this->database_id = $this->database->id;
		}
		if($this->widget_id == null)
			$this->widget_id = 1; // default widget
		
		assert($this->database->id == $this->datasetdef->database_id);

		$this->datafiletypes = \App\Models\Datafiletype::all();
		$this->widgets = \App\Models\Widget::all(); //jw:todo get widgets based on datafiletype
		//dd($this->datafiletypes);
	}

	public function save()
	{
		$regex = 'not_regex:/[<>:&\"\\\|\?\*\/]/i';  // must not contain <>:&"\|?*/
		$isNew = !$this->datasetdef;

		if($isNew)
		{
			$this->validate(
				[ 'name' => 
						[ 
						'required',  // must be provided
						Rule::unique('datasetdefs','name')->where(
							function ($query) {
								return $query->where('database_id', $this->database->id); }), // must be different than other names from this database
						$regex, // prohibit special characters 
						'max:255' // max 255 characters
						],
					'description' => 'max:255',
					'datafiletype_id' => 'required',
				]);
			$this->datasetdef = new Datasetdef();
		}
		else
		{
			$this->validate(
				[ 'name' => 
						[ 
						'required',  // must be provided
						Rule::unique('datasetdefs','name')->ignore($this->datasetdef->id)->where(
							function ($query) {
								return $query->where('database_id', $this->database->id); }), // must be different than other names from this database
						$regex, // prohibit special characters 
						'max:255' // max 255 characters
						],
					'description' => 'max:255',
					'datafiletype_id' => 'required',
				]);
		}

		$this->datasetdef->name = $this->name;
		$this->datasetdef->description = $this->description;
		$this->datasetdef->database_id = $this->database->id;
		$this->datasetdef->datafiletype_id = $this->datafiletype_id;
		$this->datasetdef->widget_id = $this->widget_id > 0 ? $this->widget_id : null;
		$this->datasetdef->save();
		$this->database->touch(); 

		session()->flash('message', $isNew ? 'Datasetdef created successfully.' : 'Datasetdef updated successfully.');

		return redirect()->route('databases.datasetdefs', $this->database);
	}

	public function render()
	{
		return view('livewire.datasetdef-form');
	}
}
