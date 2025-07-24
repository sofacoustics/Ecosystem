<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Dataset extends Model
{
	use HasFactory;

	protected $fillable = [
			'id', 'name', 'description', 'database_id'
	];

	protected static function boot()
	{
		parent::boot();
		static::deleting(function($model) {
				$model->datafiles->each->delete();
		});
	}
	/**
	 * Get the files for a dataset
	 * 
	 */
	public function datafiles(): HasMany
	{
		return $this->hasMany(Datafile::class)->orderBy('datasetdef_id');
	}

	//jw:note This creates the database->dataset relationship using standard column names
	public function database(): BelongsTo
	{
		return $this->belongsTo(Database::class);
	}

	public function missing_datafiles()
	{
		$requiredDatasetdefs = $this->database->datasetdefs()->pluck('id');
		$existingDatasetdefs = $this->datafiles->pluck('datasetdef_id');
		$missingDatasetdefs = Datasetdef::whereIn('id', $requiredDatasetdefs->diff($existingDatasetdefs))->get();
		return $missingDatasetdefs;
	}

	/*
	 * Return an HTML anchor element to this dataset
	 */
	public function link() : string
	{
		return '<a href="' . route('datasets.show', [ 'dataset' => $this ]) . '">' . $this->name . '</a>';
	}
}
