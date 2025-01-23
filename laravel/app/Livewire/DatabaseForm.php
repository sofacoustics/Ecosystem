<?php

namespace App\Livewire;

use App\Models\Database;

use Livewire\Component;

/*
 * https://neon.tech/guides/laravel-livewire-blog#implementing-the-blog-functionality
 */
class DatabaseForm extends Component
{
    public $database;
    public $title;
    public $description;

    //jw:todo rules
	protected $rules = [
		'title' => 'required',
		'description' => 'required',
	];

    public function mount($database = null)
    {
        if($database) 
        {
            $this->database = $database;
            $this->title = $database->title;
            $this->description = $database->description;
        }
    }

    public function save()
    {

        $this->validate();

        $isNew = !$this->database;

        if($isNew)
        {
            $this->database = new Database();
            $this->database->user_id = auth()->id();
        }

        $this->database->title = $this->title;
        $this->database->description = $this->description;

        $this->database->save();

        session()->flash('message', $isNew ? 'Database created successfully.' : 'Database updated successfully.');
        return redirect()->route('databases.show', $this->database);
    }

    public function render()
    {
        return view('livewire.database-form');
    }
}
