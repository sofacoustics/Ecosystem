<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\MorphTo;

class RelatedIdentifier extends Model
{
	use HasFactory;
	protected $fillable = ['id', 'relatedidentifierable_id', 'relatedidentifierable_type', ];
	
	public function relatedidentifierable(): MorphTo
	{
		return $this->morphTo(); 
	}

}
