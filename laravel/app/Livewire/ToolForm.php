<?php

namespace App\Livewire;

use App\Models\Tool;

use Livewire\Component;

class ToolForm extends Component
{
    public $tool;
    public $title;
    public $additionaltitle;
    public $additionaltitletype;
    public $description;
		public $descriptiontype;
    public $productionyear;
    public $publicationyear;
    public $language;
    public $datasources;
    public $software;
    public $processing;
    public $relatedinformation;
		public $controlledrights;
    public $additionalrights;

    //jw:todo rules
	protected $rules = [
		'title' => 'required',
		'productionyear' => 'required',
		'publicationyear' => 'required',
		'controlledrights' => 'required',
	];

    public function mount($tool = null)
    {
        if($tool) 
        {
            $this->tool = $tool;
            $this->title = $tool->title;
						$this->additionaltitle = $tool->additionaltitle;
						if ($tool->additionaltitletype == null)
							$this->additionaltitletype = null;
						else
							$this->additionaltitletype = $tool->additionaltitletype-72;
            $this->description = $tool->description;
						if ($tool->descriptiontype == null)
							$this->descriptiontype = null;
						else
							$this->descriptiontype = $tool->descriptiontype-76;
						$this->productionyear = $tool->productionyear;
						$this->publicationyear = $tool->publicationyear;
						$this->language = $tool->language;
						$this->datasources = $tool->datasources;
						$this->software = $tool->software;
						$this->processing = $tool->processing;
						$this->relatedinformation = $tool->relatedinformation;
						$this->controlledrights = $tool->controlledrights-47;
						$this->additionalrights = $tool->additionalrights;
        }
				else
				{
					$this->language = "eng"; 
					$this->controlledrights = 0; // CC BY
					$this->additionaltitletype = 0; // Subtitle
				}
    }

    public function save()
    {

        $this->validate();

        $isNew = !$this->tool;

        if($isNew)
        {
            $this->tool = new Tool();
            $this->tool->user_id = auth()->id();
        }

        $this->tool->title = $this->title;
        $this->tool->additionaltitle = $this->additionaltitle;
				if ($this->additionaltitletype == null) { $this->tool->additionaltitletype = null; }
					else { $this->tool->additionaltitletype = $this->additionaltitletype+72; }
        $this->tool->description = $this->description;
				if ($this->descriptiontype == null) { $this->tool->descriptiontype = null; }
					else { $this->tool->descriptiontype = $this->descriptiontype+76; }
        $this->tool->productionyear = $this->productionyear;
        $this->tool->publicationyear = $this->publicationyear;
        $this->tool->language = $this->language;
        $this->tool->resourcetype = 3; // fix to Dataset 
        $this->tool->resource = "SONICOM Ecosystem"; // fix 
        $this->tool->datasources = $this->datasources;
        $this->tool->software = $this->software;
        $this->tool->processing = $this->processing;
        $this->tool->relatedinformation = $this->relatedinformation;
        $this->tool->controlledrights = $this->controlledrights+47;
        $this->tool->additionalrights = $this->additionalrights;

        $this->tool->save();

        session()->flash('message', $isNew ? 'Tool created successfully.' : 'Tool updated successfully.');
        return redirect()->route('tools.show', $this->tool);
    }

    public function render()
    {
        return view('livewire.tool-form');
    }
}
