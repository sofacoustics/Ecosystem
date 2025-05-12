<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

use App\Models\User;

class ToolTableFilter extends Component
{
    public $filters = [
        'title' => '',
        'description' => '',
    ];

		public $sortField = 'title'; // Default sorting field
    public $sortAsc = true; // Default sorting order
		
    public $tools;
		
		public $user_id;

    public function mount()
    {
        $this->applyFilters();
				$user = auth()->user();
				if(empty($user))
					$this->user_id = null;
				else
					$this->user_id = $user->id;
    }

    public function applyFilters()
    {
        $query = DB::table('tools'); 

        if (!empty($this->filters['title'])) {
            $query->where('title', 'like', '%' . $this->filters['title'] . '%');
						$this->tools = $query->get();
        }

        if (!empty($this->filters['description'])) {
            $query->where('description', 'like', '%' . $this->filters['description'] . '%');
        }

        $this->tools = $query->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')->get();
    }

    public function render()
    {
				$this->applyFilters();
        return view('livewire.tool-table-filter');
    }

    public function clearFilters() {
        $this->filters = [
            'title' => '',
            'description' => '',
        ];
        $this->applyFilters();
    }
		
		public function userName($user_id)
		{
			$user=\App\Models\User::where('id',$user_id)->select('name')->get();
			return $user[0]->name;
		}
		
		public function sortBy($field)
    {
			if ($this->sortField === $field) 
			{
				$this->sortAsc = !$this->sortAsc;
			} else 
			{
				$this->sortAsc = true;
				$this->sortField = $field;
			}
			$this->applyFilters();
    }		
		
}