<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

use App\Models\Service;

class Widget extends Model
{
	use HasFactory;

	protected $fillable = [
		'id', 'name', 'description'
	];

	public function service(): belongsTo
	{
		return $this->belongsto(Service::class);
	}

	public function datafiletypes(): BelongsToMany
	{
		return $this->belongsToMany(Datafiletype::class);
		// By default, Laravel assumes:
		// - Pivot table: 'datafiletype_widget'
		// - Foreign key on pivot for Datafiletype: 'datafiletype_id'
		// - Foreign key on pivot for Widget: 'widget_id'
	}
	
		// Returns 0 if the widget is not active (for the given datafiletype)
		// and >0 else
	public function is_active(Datafiletype $datafiletype)
	{
		$active = $datafiletype->activewidgets;
		$myactive = $active->where('id',$this->id);
		return count($myactive);
	}
}
