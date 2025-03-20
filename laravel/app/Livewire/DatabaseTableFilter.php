<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

use App\Models\User;
use App\Models\Dataset;

class DatabaseTableFilter extends Component
{
    public $filters = [
        'title' => '',
        'description' => '',
        'descriptiontype' => '',
    ];

		public $sortField = 'title'; // Default sorting field
    public $sortAsc = true; // Default sorting order
		
    public $descriptiontypes = ['Other', 'inactive', 'pending']; // Example descriptiontypes.
		
    public $databases;

    public function mount()
    {
        $this->applyFilters();
				$this->xx = 0;
    }

    public function applyFilters()
    {
        $query = DB::table('databases'); // Replace 'users' with your table title.

        if (!empty($this->filters['title'])) {
            $query->where('title', 'like', '%' . $this->filters['title'] . '%');
						$this->databases = $query->get();
        }

        if (!empty($this->filters['description'])) {
            $query->where('description', 'like', '%' . $this->filters['description'] . '%');
        }

        if (!empty($this->filters['descriptiontype'])) {
            $query->where('descriptiontype', $this->filters['descriptiontype']);
        }
        $this->databases = $query->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')->get();
    }

    public function render()
    {
				$this->applyFilters();
        return view('livewire.database-table-filter');
    }

    public function clearFilters() {
        $this->filters = [
            'title' => '',
            'description' => '',
            'descriptiontype' => '',
        ];
        $this->applyFilters();
    }
		
		public function userName($user_id)
		{
			$user=\App\Models\User::where('id',$user_id)->select('name')->get();
			return $user[0]->name;
		}
		
		public function countDatasets($database_id)
		{
			$datasets=\App\Models\Dataset::where('database_id',$database_id)->get();
			return count($datasets);
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