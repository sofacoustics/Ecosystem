<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\MorphTo;

class Creator extends Radar
{
	use HasFactory;
	
	protected $fillable = ['id', 'creatorable_id', 'creatorable_type'];

	public function creatorable(): MorphTo
	{
		return $this->morphTo(); 
	}
}
