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
	private const DEFAULT_FILTERS = [
		'title' => '',
		'productionyear' => '',
		'keyword' => '',
	];

	public $filters = self::DEFAULT_FILTERS;

	private const SORTABLE_FIELDS = ['visible', 'doi', 'title', 'productionyear', 'datasets', 'user', 'keywords', 'updated_at'];

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
		$this->normalizeFilters();

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
		// 'Uploaded by' is shown to logged-in users only: do not reveal the order to guests
		if ($this->sortField === 'user' && !auth()->check())
		{
			$this->sortField = 'title';
		}
		$direction = $this->sortAsc ? 'asc' : 'desc';

		$query->withCount('datasets'); // datasets_count, used for display and sorting

		switch ($this->sortField)
		{
			case 'doi':
				// sort by DOI state: 0 = no DOI, 1 = DOI assigned, 2 = persistently published (radar_status 4)
				$query->orderByRaw("CASE WHEN doi IS NULL OR doi = '' THEN 0 WHEN radar_status = 4 THEN 2 ELSE 1 END " . $direction)
					->orderBy('title', 'asc');
				break;
			case 'datasets':
				$query->orderBy('datasets_count', $direction)
					->orderBy('title', 'asc');
				break;
			case 'user':
				// name of the user who uploaded the database
				$query->orderBy(User::select('name')->whereColumn('users.id', 'databases.user_id')->limit(1), $direction)
					->orderBy('title', 'asc');
				break;
			case 'keywords':
				// first keyword as displayed (lowest id; the order can be changed by the owner with up/down),
				// databases without keywords at the end in both directions
				$query->orderByRaw('NOT EXISTS (SELECT 1 FROM keywords WHERE keywords.keywordable_id = databases.id AND keywords.keywordable_type = ?)', [Database::class])
					->orderBy(Keyword::select('keywordName')
						->whereColumn('keywords.keywordable_id', 'databases.id')
						->where('keywords.keywordable_type', Database::class)
						->orderBy('keywords.id')
						->limit(1), $direction)
					->orderBy('title', 'asc');
				break;
			default:
				$query->orderBy($this->sortField, $direction);
		}

		$this->databases = $query->get();
	}

	// $filters is a public Livewire property and can be replaced by a crafted request
	// (e.g. filters = [] or arrays as values): keep only the known keys, fill in missing
	// ones and drop invalid values, so that such requests show the unfiltered list instead of a 500.
	private function normalizeFilters()
	{
		$filters = is_array($this->filters) ? $this->filters : [];
		$normalized = self::DEFAULT_FILTERS;
		foreach (self::DEFAULT_FILTERS as $key => $default)
		{
			if (!array_key_exists($key, $filters) || !is_scalar($filters[$key]))
				continue;
			$normalized[$key] = is_int($default) ? (int) $filters[$key] : (string) $filters[$key];
		}
		$this->filters = $normalized;
	}

	public function render()
	{
		$this->applyFilters();
		return view('livewire.database-table-filter');
	}

	public function clearFilters() 
	{
		$this->filters = self::DEFAULT_FILTERS;
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