<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\MorphTo;

class Rightsholder extends Radar
{
	use HasFactory;
	protected $fillable = ['id', 'rightsholderable_id', 'rightsholderable_type', ];
	
	public function rightsholderable(): MorphTo
	{
		return $this->morphTo(); 
	}
}
