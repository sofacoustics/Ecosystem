<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tool extends Model
{
    use HasFactory;

    protected $fillable = [
        'id', 'name', 'description'
    ];
		
		public function creators(): HasMany
    {
        return $this->hasMany(Creator::class);
    }
}
