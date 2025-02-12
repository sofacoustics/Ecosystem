<?php

namespace App\Livewire;

use Livewire\Component;

use App\Models\Datasetdef;
use App\Models\Datafiletype;

class DatasetdefForm extends Component
{
	public $database;
	public $datasetdef;
	public $name;
	public $database_id;
	public $datafiletype_id;
	public $widget_id;

	public $datafiletypes; // get from Datafiletyp
	public $widgets; // get from Widgets

	protected $rules = [
		'name' => 'required',
		'datafiletype_id' => 'required',
	];

	public function mount($database, $datasetdef = null)
	{
		$this->database = $database;
		if($datasetdef)
		{
			$this->datasetdef = $datasetdef;
			$this->name = $datasetdef->name;
			$this->database_id = $datasetdef->database_id;
            $this->datafiletype_id = $datasetdef->datafiletype_id;
            $this->widget_id = $datasetdef->widget_id;
		}
		else
		{
			$this->datafiletype_id = null;
			$this->database_id = $this->database->id;
		}
		assert($this->database->id == $this->datasetdef->id);

        $this->datafiletypes = \App\Models\Datafiletype::all();
        $this->widgets = \App\Models\Widget::all(); //jw:todo get widgets based on datafiletype
        //dd($this->datafiletypes);
	}

	public function save()
	{
		$this->validate();

		$isNew = !$this->datasetdef;

		if($isNew)
		{
			$this->datasetdef = new Datasetdef();
		}

		$this->datasetdef->name = $this->name;
		$this->datasetdef->database_id = $this->database->id;
        $this->datasetdef->datafiletype_id = $this->datafiletype_id;
        $this->datasetdef->widget_id = $this->widget_id > 0 ? $this->widget_id : null;
		$this->datasetdef->save();

       session()->flash('message', $isNew ? 'Datasetdef created successfully.' : 'Datasetdef updated successfully.');

        return redirect()->route('databases.datasetdefs', $this->database);
	}

    public function render()
    {
        return view('livewire.datasetdef-form');
    }
}
