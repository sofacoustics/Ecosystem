<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Creator extends Model
{
    use HasFactory;
		
		protected $fillable = ['id', 'database_id', 'creatorName', 'givenName'];
		
		public function database(): BelongsTo
    {
        return $this->belongsTo(Database::class); 
    }
		
    // Define the possible categories (optional)
    public const nameIdentifierSchemeCategories = [
        'ORCID',
        'ROR',
    ];
}
