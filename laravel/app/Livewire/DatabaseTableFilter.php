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

		$this->databases = $query->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')->get();
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
				$s = $s . "; " . $keyword->keywordName;
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