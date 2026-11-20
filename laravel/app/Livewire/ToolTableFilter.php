<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

use App\Models\User;
use App\Models\Tool;
use App\Models\Keyword;

class ToolTableFilter extends Component
{
	public $filters = [
		'title' => '',
		'productionyear' => '',
		'keyword' => '',
		'resourcetype' => 0,
	];

	private const SORTABLE_FIELDS = ['doi', 'title', 'productionyear', 'resourcetype', 'updated_at'];

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
		// One query for all filters (before, the keyword filter replaced the query
		// and a title filter set before was lost).
		$query = Tool::query();

		if (!empty($this->filters['title'])) 
		{
			$query->where('title', 'like', '%' . $this->filters['title'] . '%');
		}

		if (!empty($this->filters['keyword'])) 
		{
			$query->whereHas('keywords', function ($q) {
				$q->where('keywordName', 'like', '%' . $this->filters['keyword'] . '%'); });
		}

		if (!empty($this->filters['productionyear'])) 
		{
			$query->where('productionyear', 'like', '%' . $this->filters['productionyear'] . '%');
		}

		if ($this->filters['resourcetype']!=0)
		{
			$query->where('resourcetype', '=', $this->filters['resourcetype']);
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

		$this->tools = $query->get();
	}

	public function render()
	{
		$this->applyFilters();
		return view('livewire.tool-table-filter');
	}

	public function clearFilters() 
	{
		$this->filters = [
			'title' => '',
			'productionyear' => '',
			'keyword' => '',
			'resourcetype' => 0,
		];
		$this->applyFilters();
	}
	
	public function userName($user_id)
	{
		$user=\App\Models\User::where('id',$user_id)->select('name')->get();
		return $user[0]->name;
	}
	
	public function getKeywords($tool_id)
	{
		$keywords=\App\Models\Keyword::where('keywordable_type','App\Models\Tool')->where('keywordable_id',$tool_id)->get();
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