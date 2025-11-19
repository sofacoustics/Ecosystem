<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

use App\Models\Dataset;


class DatasetTableFilter extends Component
{
	public $filters = [
		'name' => '',
		'description' => '',
	];

	public $sortField = 'id'; // Default sorting field
	public $sortAsc = true; // Default sorting order
	
	public $database;
	public $datasets;

	public function mount()
	{
		$this->applyFilters();
	}

	public function applyFilters()
	{
		// $query = Dataset::query();
		$query = Dataset::where('database_id',$this->database->id);
		
		if (!empty($this->filters['name'])) 
		{
			$query->where('name', 'like', '%' . $this->filters['name'] . '%');
		}
		// else
			// $query = Database::query();

		if (!empty($this->filters['description'])) 
		{
			$query->where('description', 'like', '%' . $this->filters['description'] . '%');
		}

		if($this->sortField === 'count')
			$this->datasets = $query->withCount('datafiles')->orderBy('datafiles_count', $this->sortAsc ? 'asc' : 'desc')->get();
		else
			$this->datasets = $query->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')->get();
	}

	public function render()
	{
		$this->applyFilters();
		return view('livewire.dataset-table-filter');
	}

	public function clearFilters() 
	{
		$this->filters = [
			'name' => '',
			'description' => '',
		];
		$this->applyFilters();
	}
	
	public function countDatafiles($dataset_id)
	{
		$datafiles=\App\Models\Datafiles::where('dataset_id',$dataset_id)->get();
		return count($datafiles);
	}

	public function sortBy($field)
	{
		if ($this->sortField === $field) 
		{
			$this->sortAsc = !$this->sortAsc;
		} 
		else 
		{
			$this->sortAsc = true;
			$this->sortField = $field;
		}
		$this->applyFilters();
	}		
}