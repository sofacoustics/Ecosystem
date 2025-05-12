<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tool extends Model
{
    use HasFactory;

    protected $fillable = [
        'id', 'title', 'description'
    ];
		
		
		public function comments()
	{
		return $this->hasMany(Comment::class);
	}

		public function creators(): HasMany
    {
        return $this->hasMany(Creator::class);
    }
}
