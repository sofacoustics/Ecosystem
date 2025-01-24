<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Creator extends Model
{
    use HasFactory;
		
		protected $fillable = [
        'id',
        'category', 
    ];

    // Define the possible categories (optional)
    public const nameIdentifierScheme = [
        'ORCID',
        'ROR',
    ];
}
