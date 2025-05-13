<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Tool extends Model
{
	use HasFactory;

	protected $fillable = [
			'id', 'title', 'description'
	];
		
	public function comments(): MorphMany
	{
		return $this->morphMany(Comment::class, 'commentable');
	}

	public function creators(): HasMany
	{
			return $this->hasMany(Creator::class);
	}
}
