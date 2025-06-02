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

		if (!empty($this->filters['title'])) 
		{
			$query->where('title', 'like', '%' . $this->filters['title'] . '%');
			$this->tools = $query->get();
		}

		if (!empty($this->filters['keyword'])) 
		{
				$query = Tool::whereHas('keywords', function ($q) {
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

		$this->tools = $query->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')->get();
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