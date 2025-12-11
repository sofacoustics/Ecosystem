<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

use App\Models\Dataset;


class DatasetTableFilter extends Component
{
	use WithPagination;
	
	public $filters = [
		'name' => '',
		'description' => '',
	];

	public $sortField = 'name'; // Default sorting field
	public $sortAsc = true; // Default sorting order
	public $database;

	public function mount()
	{
		
	}

	public function applyFilters()
	{
		$query = Dataset::where('database_id',$this->database->id);
		
		if (!empty($this->filters['name'])) 
		{
			$query->where('name', 'like', '%' . $this->filters['name'] . '%');
		}

		if (!empty($this->filters['description'])) 
		{
			$query->where('description', 'like', '%' . $this->filters['description'] . '%');
		}

		$sF = $this->sortField;
		$sA = $this->sortAsc;
		switch($sF)
		{
			case 'count':
				$datasets = $query->withCount('datafiles')->orderBy('datafiles_count', $sA ? 'asc' : 'desc')->paginate(15)->withQueryString();
				break;
			case 'name':
				$datasets = $query->orderByRaw('LENGTH(' . $sF. ') ' . ($sA ? 'asc' : 'desc'))->
											orderBy($sF, $sA ? 'asc' : 'desc')->
											paginate(15)->
											withQueryString();
				break;
			default:
				$datasets = $query->orderBy($sF, $sA ? 'asc' : 'desc')->paginate(15);
		}
		
		return $datasets;		
	}

	public function render()
	{
		$datasets = $this->applyFilters();
		return view('livewire.dataset-table-filter', ['datasets' => $datasets]);
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