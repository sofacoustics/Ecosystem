<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Creator extends Model
{
    use HasFactory;
		
		protected $fillable = [
        'id', 'database_id', 'creatorName', 'givenName',
    ];

    // Define the possible categories (optional)
    public const nameIdentifierSchemeCategories = [
        'ORCID',
        'ROR',
    ];
		
		public function database()
    {
        return $this->belongsTo(Database::class); 
    }
}
