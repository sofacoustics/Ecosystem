<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\MorphTo;

class SubjectArea extends Model
{
	use HasFactory;
	protected $fillable = ['id', 'subjectareaable_id', 'subjectareaable_type', ];
	
	public function subjectareaable(): MorphTo
	{
		return $this->morphTo(); 
	}
}
