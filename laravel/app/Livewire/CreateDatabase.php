<?php

/*
 *
 * Created with php artisan make:livewire CreateDatabase (see https://livewire.laravel.com/docs/components)
 */

namespace App\Livewire;

use Livewire\Attributes\Validate;
use Livewire\Component;
use App\Models\Database;
use Auth;

class CreateDatabase extends Component
{
    #[Validate('required')]
    public $name = "";
    #[Validate('required')]
    public $description = "";
    public $user_id = " ";

    public function mount()
    {
        if(Auth::check()) {
            $this->user_id = Auth::user()->id;
        }
    }

    public function save()
    {
        $this->validate();

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
