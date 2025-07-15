<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\MorphTo;

class Publisher extends Radar
{
	use HasFactory;
	protected $fillable = ['id', 'publisherable_id', 'publisherable_type', ];
	
	public function publisherable(): MorphTo
	{
		return $this->morphTo(); 
	}
	
}
