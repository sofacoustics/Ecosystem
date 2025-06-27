<?php

namespace App\Models; 

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Metadataschema extends Model
{
	use HasFactory;

	/*
	 * Lists all rows for a specific 'name' in their 'display' format
	 */
	static function list($name)
	{
		$rr = Metadataschema::where('name',$name)->select('display')->get();
		return $rr;
	}

	/*
	 * Lists all rows for a specific 'name' in their 'id' format
	 */
	static function list_ids($name)
	{
		$rr = Metadataschema::where('name',$name)->select('id')->get();
		return $rr;
	}

	/*
	 * Return the 'value' field for this id. This is necessary for valid RADAR JSON
	 *  The id can be negative to indicate some special case. Thus, we here look only 
	 *  at the absolute value of the id.
	 */
	static function value($id)
	{
		$result = Metadataschema::where('id', abs($id))->select('value')->get();
		return $result[0]->attributes['value'];
	}

	/*
	 * Return the 'display' value for this id. This is something the user will
	 * find easier to read than the 'value'.
	 *  The id can be negative to indicate some special case. Thus, we here look only 
	 *  at the absolute value of the id.
	 */
	static function display($id)
	{
		$result = Metadataschema::where('id', abs($id))->select('display')->get();
		return $result[0]->attributes['display'];
	}
}
