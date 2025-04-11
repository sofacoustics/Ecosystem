<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use HasFactory;
		protected $fillable = ['id', 'database_id', 'user_id'];
		
		public function database(): BelongsTo
    {
        return $this->belongsTo(Database::class); 
    }
		
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}


