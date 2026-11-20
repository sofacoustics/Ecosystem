<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

use App\Models\User;
use App\Models\Dataset;
use App\Models\Database;
use App\Models\Keyword;


class DatabaseTableFilter extends Component
{
	public $filters = [
		'title' => '',
		'productionyear' => '',
		'keyword' => '',
	];

	private const SORTABLE_FIELDS = ['visible', 'doi', 'title', 'productionyear', 'updated_at'];

	public $sortField = 'title'; // Default sorting field
	public $sortAsc = true; // Default sorting order
	
	public $databases;
	
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
		if (!empty($this->filters['keyword'])) 
		{
			$query = Database::whereHas('keywords', function ($q) {
				$q->where('keywordName', 'like', '%' . $this->filters['keyword'] . '%'); });
		}
		else
			$query = Database::query();

		if (!empty($this->filters['title'])) 
		{
			$query->where('title', 'like', '%' . $this->filters['title'] . '%');
		}

		if (!empty($this->filters['productionyear'])) 
		{
			$query->where('productionyear', 'like', '%' . $this->filters['productionyear'] . '%');
		}

		// sortField is a public Livewire property and can be changed by the
		// client: only allow the columns offered in the table.
		if (!in_array($this->sortField, self::SORTABLE_FIELDS, true))
		{
			$this->sortField = 'title';
		}
		$direction = $this->sortAsc ? 'asc' : 'desc';

		if ($this->sortField === 'doi')
		{
			// sort by DOI state: 0 = no DOI, 1 = DOI assigned, 2 = persistently published (radar_status 4)
			$query->orderByRaw("CASE WHEN doi IS NULL OR doi = '' THEN 0 WHEN radar_status = 4 THEN 2 ELSE 1 END " . $direction)
				->orderBy('title', 'asc');
		}
		else
		{
			$query->orderBy($this->sortField, $direction);
		}

		$this->databases = $query->get();
	}

	public function render()
	{
		$this->applyFilters();
		return view('livewire.database-table-filter');
	}

	public function clearFilters() 
	{
		$this->filters = [
			'title' => '',
			'productionyear' => '',
			'keyword' => '',
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
	
	public function getKeywords($database_id)
	{
		$keywords=\App\Models\Keyword::where('keywordable_type','App\Models\Database')->where('keywordable_id',$database_id)->get();
		$s="";
		foreach($keywords as $index => $keyword)
		{
			if($index>0)
				$s = $s . "\n" . $keyword->keywordName;
			else
				$s = $keyword->keywordName;
		}
		return $s;
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