<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use App\Models\Database;
use App\Models\Dataset;
/*
 * Livewire component to be used to create or edit a dataset
 */
class DatasetForm extends Component
{
	public Database $database; // Database model
	public $dataset; // Dataset model. Note: since this can be null, we can't specify it as a Dataset

	public $name;
	public $description;
	
	protected $messages = [
		'name.required' => 'The name cannot be empty.',
		'name.unique' => 'There is already a dataset with this name in your database. Choose an other name.',
	];

	public function mount($dataset = null)
	{
			if($dataset)
			{
					$this->dataset = $dataset;
					$this->database = $dataset->database;
					assert($this->database->id == $dataset->database_id);
					$this->name = $dataset->name;
					$this->description = $dataset->description;
			}
	}

	public function save()
	{
			$isNew = !$this->dataset;

			if($isNew)
			{		// create
				$this->validate([ 'name' => [ 
					'required',  // must be provided
					Rule::unique('datasets','name')->where(
						function ($query) {
							return $query->where('database_id', $this->database->id); }) // must be different than other names from this database
					]
				]);
				$this->dataset = new Dataset();
				$this->dataset->name = $this->name;
				$this->dataset->description = $this->description;
				$this->dataset->database_id = $this->database->id;
				$this->dataset->save();
				session()->flash('message', 'Dataset created successfully.');
				return redirect()->route('datasets.show', $this->dataset);
			}
			else 
			{		// update
				$this->validate([ 'name' => [ 
					'required', // must be given
					Rule::unique('datasets','name')->ignore($this->dataset->id)->where(
						function ($query) {
							return $query->where('database_id', $this->database->id); }) // must be different than other names from this database
					]
				]);
				$this->dataset->name = $this->name;
				$this->dataset->description = $this->description;
				$this->dataset->save();
				session()->flash('message', 'Dataset updated successfully.');
				return redirect()->route('datasets.show', $this->dataset);
			}
	}

	public function render()
	{
			return view('livewire.dataset-form');
	}
}
