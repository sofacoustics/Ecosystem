<?php

namespace App\Livewire;

use Livewire\Component;

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

	protected $rules = [
		'name' => 'required',
        'description' => 'required', /* really required? */
	];

    public function mount($dataset = null)
    {
        if($dataset)
        {
            $this->dataset = $dataset;
            $this->name = $dataset->name;
            $this->description = $dataset->description;
            assert($this->database->id == $dataset->database_id);
        }
    }

    public function save()
    {
        $this->validate();

        $isNew = !$this->dataset;

        if($isNew)
        {
            $this->dataset = new Dataset();
        }

        $this->dataset->name = $this->name;
        $this->dataset->description = $this->description;
        $this->dataset->database_id = $this->database->id;

        $this->dataset->save();

        //dd($this->dataset);

        session()->flash('message', $isNew ? 'Dataset created successfully.' : 'Dataset updated successfully.');
        return redirect()->route('datasets.show', $this->dataset);
    }

    public function render()
    {
        return view('livewire.dataset-form');
    }
}
