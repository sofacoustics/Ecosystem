<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

// Define the possible categories for the nameIdentifierSchemes
const nameIdentifierSchemeCategories = [
	'Other',
	'ORCID',
	'ROR' ];

class Creator extends Model
{
    use HasFactory;
		
		protected $fillable = ['id', 'database_id'];
		
		public function database(): BelongsTo
    {
        return $this->belongsTo(Database::class); 
    }
		
		public function nameIdentifierScheme($x)
		{
			if($x == null) $x=0;
			if($x>2) $x=2;
			if($x<0) $x=0;
			return nameIdentifierSchemeCategories[$x];
		}
		
}
