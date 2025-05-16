<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\MorphTo;

// Define the possible categories for the keywordScheme
const keywordSchemeCategories = [
	'Other',
	'GND' ];

class Keyword extends Model
{
	use HasFactory;
	protected $fillable = ['id', 'keywordable_id', 'keywordable_type', ];
	
	public function keywordable(): MorphTo
	{
		return $this->morphTo(); 
	}

	public static function keywordScheme($x)
	{
		if($x == null) $x=0;
		if($x>1) $x=1;
		if($x<0) $x=0;
		return keywordSchemeCategories[$x]; // categories defined in Creator.php
	}

	public static function keywordSchemeList()
	{
		return keywordSchemeCategories;
	}
}
