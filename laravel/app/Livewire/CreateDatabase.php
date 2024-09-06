<?php

/*
 *
 * Created with php artisan make:livewire CreateDatabase (see https://livewire.laravel.com/docs/components)
 */

namespace App\Livewire;

use Livewire\Component;
use App\Models\Database;
use Auth;

class CreateDatabase extends Component
{
    public $name = "";
    public $description = "";
    public $user_id = " ";

    public function mount()
    {
        $this->user_id = Auth::user()->id;
    }

    public function save()
    {
        Database::create(
            $this->only(['name', 'description', 'user_id'])
        );

        session()->flash('status', 'Database successfully updated.');

        return $this->redirect('/databases');
    }

    public function render()
    {
        return view('livewire.create-database');
    }
}
