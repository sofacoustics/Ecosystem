<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Datafiletype extends Model
{
	use HasFactory;

	protected $fillable = [
		'id',
		'name',
		'description'
	];
	
	public function widgets(): BelongsToMany
	{
		return $this->belongsToMany(Widget::class)->orderBy('name', 'asc');
		// By default, Laravel assumes:
		// - Pivot table: 'datafiletype_widget'
		// - Foreign key on pivot for Datafiletype: 'datafiletype_id'
		// - Foreign key on pivot for Widget: 'widget_id'
	}

	public function activewidgets(): BelongsToMany
	{
		return $this->belongsToMany(Widget::class)->where('is_active',true)->orderBy('name', 'asc');
	}

}
