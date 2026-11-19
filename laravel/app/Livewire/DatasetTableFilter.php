<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

use App\Models\Dataset;


class DatasetTableFilter extends Component
{
	use WithPagination;
	
	public $filters = [
		'name' => '',
		'description' => '',
	];

	private const SORTABLE_FIELDS = ['id', 'name', 'count', 'description', 'updated_at', 'created_at'];

	public $sortField = 'name'; // Default sorting field
	public $sortAsc = true; // Default sorting order
	public $database;
	
	public $perPage = 20;
	public $perPageOptions = [10, 20, 50, 100, 200];

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

		// sortField is a public Livewire property and can be changed by the
		// client: only allow the columns offered in the table.
		if (!in_array($this->sortField, self::SORTABLE_FIELDS, true))
		{
			$this->sortField = 'name';
		}
		$sF = $this->sortField;
		$sA = (bool) $this->sortAsc;
		switch($sF)
		{
			case 'count':
				$datasets = $query->withCount('datafiles')->orderBy('datafiles_count', $sA ? 'asc' : 'desc')->paginate($this->perPage)->withQueryString();
				break;
			case 'name':
				$datasets = $this->paginateByNaturalName($query, $sA);
				break;
			default:
				$datasets = $query->orderBy($sF, $sA ? 'asc' : 'desc')->paginate($this->perPage);
		}
		
		return $datasets;		
	}

	/**
	 * Natural sorting by name (e.g. "2. x" before "10. x", "HRTF_9" before "HRTF_10").
	 *
	 * MySQL has no natural sort, so only id and name of all filtered datasets
	 * are loaded and sorted in PHP (strnatcasecmp, id as tie-breaker). Then the
	 * full rows of the current page are loaded and returned in a paginator.
	 */
	private function paginateByNaturalName($query, bool $ascending): LengthAwarePaginator
	{
		$rows = (clone $query)->get(['id', 'name'])->all();
		usort($rows, function ($a, $b) use ($ascending) {
			$cmp = strnatcasecmp((string) $a->name, (string) $b->name);
			if ($cmp === 0)
			{
				$cmp = $a->id <=> $b->id;
			}
			return $ascending ? $cmp : -$cmp;
		});

		$perPage = (int) $this->perPage > 0 ? (int) $this->perPage : 20;
		$page = max(1, (int) $this->getPage());
		$pageIds = array_map(fn ($row) => $row->id,
			array_slice($rows, ($page - 1) * $perPage, $perPage));

		// load the full rows of the current page and restore the sorted order
		$items = Dataset::whereIn('id', $pageIds)->get()->keyBy('id');
		$pageItems = collect($pageIds)->map(fn ($id) => $items->get($id))->filter()->values();

		return new LengthAwarePaginator($pageItems, count($rows), $perPage, $page, [
			'path' => Paginator::resolveCurrentPath(),
			'pageName' => 'page',
		]);
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