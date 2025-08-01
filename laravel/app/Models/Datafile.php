<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Storage;


class Datafile extends Model
{
	use HasFactory;

	protected $fillable = [
		'id', 'name', 'location', 'dataset_id', 'datasetdef_id'
	];

	public function dataset(): BelongsTo
	{
		return $this->belongsTo(Dataset::class);
	}

	public function datasetdef(): BelongsTo
	{
		return $this->belongsTo(Datasetdef::class);
	}

	/*
		Return the directory containing this file
	*/
	public function directory() : string
	{

		$database_id = $this->dataset->database->id;
		$dataset_id = $this->dataset->id;
		$datafile_id = $this->id;
		$directory = $database_id."/".$dataset_id."/".$datafile_id;
		return "$database_id/$dataset_id/$datafile_id";
	}

	public function path()
	{
		return $this->directory() . "/" . $this->name;
	}

	/*
		Return the absolute path to the data file
	*/
	public function absolutepath()
	{
		//
		// Storage path examples
		//
		// Storage::path(''));							-> "/home/jw/git/isf-sonicom-laravel/laravel/storage/app/"
		// Storage::path('public');						-> "/home/jw/git/isf-sonicom-laravel/laravel/storage/app/public"
		// Storage::path('sonicom-data');				-> "/home/jw/git/isf-sonicom-laravel/laravel/storage/app/sonicom-data" !!!
		//
		// Storage::disk('public')->path(''));			-> "/home/jw/git/isf-sonicom-laravel/laravel/storage/app/public/"
		// Storage::disk('local')->path(''));			-> "/home/jw/git/isf-sonicom-laravel/laravel/storage/app/"
		// Storage::disk('sonicom-data')->path('')		-> "/mnt/fileserver/Users/jw/sonicom-data/dev/public/"
		//
		// storage_path(''));							-> "/home/jw/git/isf-sonicom-laravel/laravel/storage"
		//
		// Storage::url('');							-> "/storage/"
		// Storage::disk('sonicom-data')->url('')	   -> "https:/sonicom-jw-local.local/data/"
		//
		$path = $this->path();

		$absolutepath = Storage::disk('sonicom-data')->path($path); // e.g. "/mnt/sonicom-data/dev/app/public/3/18/102/hrtf b_nh4.sofa"
		return $absolutepath;
	}

	public function url()
	{
		// Storage::url() prepends path with /storage
		// Therefore we need Storage::disk('sonicom-data')->url($this->path);
		return Storage::disk('sonicom-data')->url($this->path());
	}

	/**
	 * Return the publicly available asset we can then use in HTML code.
	 *
	 * $suffix: A suffix to add to the asset (since this appears to be difficult to do in livewire syntax)
	 * $notimestamp: Set to anything to prevent adding the time stamp. Required for STL-based views.
	 */
	public function asset($suffix = "", $notimestamp = null)
	{
		$pathwithsuffix = $this->absolutepath().$suffix;
		// check asset exists and return.
		if(file_exists($pathwithsuffix))
			if($notimestamp == null)
					// Add a file time modification query string to force reload, if file has changed
				return $this->url().$suffix.'?'.filemtime($pathwithsuffix);
			else
				return $this->url().$suffix;
		return "";
	}

	public function isImage()
	{
		if($this->datasetdef->datafiletype->id == 1)
			return true;
		return false;

	}
}
