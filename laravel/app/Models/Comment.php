<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Comment extends Model
{
    use HasFactory;
		protected $fillable = ['id', 'commentable_id', 'commentable_type', 'user_id'];
		
		public function commentable(): MorphTo
    {
			return $this->morphTo(); 
    }
		
    public function user(): BelongsTo
    {
			return $this->belongsTo(User::class);
    }
}


