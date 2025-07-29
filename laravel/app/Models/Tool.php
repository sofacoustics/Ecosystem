<?php

namespace App\Models;

use App\Observers\ToolObserver;
use App\Services\ToolfileRadarFileBridge;

use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Storage;

use App\Models\Metadataschema;

#[ObservedBy([ToolObserver::class])]
class Tool extends Model
{
	use HasFactory;

	protected $fillable = [
			'id', 'title', 'descriptiongeneral'
	];
		
	public function directory() : string
	{
		$tool_id = $this->id;
		$directory = "tools/".$tool_id; // We store in the same directory as the data, but in a subdirectory /tools 
		return $directory;
	}

	public function path()
	{
		return $this->directory() . "/" . $this->filename;
	}

	public function absolutepath()
	{
		//
		// Storage path examples
		//
		// Storage::path(''));                          -> "/home/jw/git/isf-sonicom-laravel/laravel/storage/app/"
		// Storage::path('public');                     -> "/home/jw/git/isf-sonicom-laravel/laravel/storage/app/public"
		// Storage::path('sonicom-data');               -> "/home/jw/git/isf-sonicom-laravel/laravel/storage/app/sonicom-data" !!!
		//
		// Storage::disk('public')->path(''));          -> "/home/jw/git/isf-sonicom-laravel/laravel/storage/app/public/"
		// Storage::disk('local')->path(''));           -> "/home/jw/git/isf-sonicom-laravel/laravel/storage/app/"
		// Storage::disk('sonicom-data')->path('')      -> "/mnt/fileserver/Users/jw/sonicom-data/dev/public/"
		//
		// storage_path(''));                           -> "/home/jw/git/isf-sonicom-laravel/laravel/storage"
		//
		// Storage::url('');                            -> "/storage/"
		// Storage::disk('sonicom-data')->url('')      -> "https:/sonicom-jw-local.local/data/"
		//
		$path = $this->path();
		$absolutepath = Storage::disk('sonicom-data')->path($path); // e.g. "/mnt/sonicom-data/dev/app/public/tools/3/amtoolbox.zip"
		return $absolutepath;
	}

	public function url()
	{
			// Storage::url() prepends path with /storage
			// Therefore we need Storage::disk('sonicom-data')->url($this->path);
		return Storage::disk('sonicom-data')->url($this->path());
	}

	public function filesize()
	{
		$fullPath = $this->absolutepath();
		if(file_exists($fullPath))
			return filesize($fullPath);
		else
			return null;
	}
	
	/**
	 * Return the publicly available asset we can then use in HTML code.
	 *
	 * $suffix: A suffix to add to the asset (since this appears to be difficult to do in livewire syntax)
	 */
	public function asset($suffix = "")
	{
		$pathwithsuffix = $this->absolutepath().$suffix;
		// check asset exists and return.
		if(file_exists($pathwithsuffix))
				// Add a file time modification query string to force reload, if file has changed
			return $this->url().$suffix.'?'.filemtime($pathwithsuffix);
		else
				return "";
	}
	
	public function removefile()
	{
		if ($this->filename)
		{
			if(Storage::disk('sonicom-data')->exists($this->directory()))
			{
				if(!Storage::disk('sonicom-data')->deleteDirectory($this->directory()))
				{
					app('log')->warning('Failed to delete tool file from disk', [
						'feature' => 'tool',
						'tool_id' => $this->id,
						'filename' => $this->filename,
						'directory' => $this->directory()
					]);
					return false;
				}
				else
				{
					app('log')->info('Deleted tool file from disk', [
						'feature' => 'tool',
						'tool_id' => $this->id,
						'filename' => $this->filename,
						'directory' => $this->directory()
					]);
				}
			}
		}
		if($this->file_radar_id)
		{
			$start = microtime(true);
			$radar = new ToolfileRadarFileBridge($this);
			if(!$radar->delete())
			{
				app('log')->warning('Failed to delete tool file from RADAR', [
					'feature' => 'tool-radar-dataset',
					'tool_id' => $this->id,
					'radar_id' => $this->radar_id,
					'file_radar_id' => $this->file_radar_id,
					'target_url' => config('services.radar.baseurl'),
					'duration' => microtime(true) - $start
				]);
				return false;
			}
		}
		$this->filename = null;
		$this->save();
	}

	public function user(): BelongsTo
	{
		return $this->belongsTo(User::class); // Specifying this relationship connects the column user_id with the User table
	}

	public function comments(): MorphMany
	{
		return $this->morphMany(Comment::class, 'commentable');
	}

	public function creators()
	{
		return $this->morphMany(Creator::class, 'creatorable');
	}

	public function publishers()
	{
		return $this->morphMany(Publisher::class, 'publisherable');
	}
		
	public function subjectareas()
	{
		return $this->morphMany(SubjectArea::class, 'subjectareaable');
	}

	public function rightsholders()
	{
		return $this->morphMany(Rightsholder::class, 'rightsholderable');
	}

	public function keywords()
	{
		return $this->morphMany(Keyword::class, 'keywordable');
	}

	public function relatedidentifiers()
	{
		return $this->morphMany(RelatedIdentifier::class, 'relatedidentifierable');
	}

	static function additionaltitletypeDisplay($additionaltitletype)
	{
		return \App\Models\Metadataschema::display($additionaltitletype);
	}

	static function additionaltitletypesList()
	{
		return \App\Models\Metadataschema::list('additionalTitleType');
	}

	static function subjectareaDisplay($subjectareaindex)
	{
		return \App\Models\Metadataschema::display($subjectareaindex);
	}

	static function subjectareaValue($subjectareaindex)
	{
		return \App\Models\Metadataschema::value($subjectareaindex);
	}

	static function subjectareasList()
	{
		return \App\Models\Metadataschema::list('subjectArea');
	}

	static function resourcetypeDisplay($resourcetype)
	{
		return \App\Models\Metadataschema::display($resourcetype);
	}

	static function resourcetypeValue($resourcetype)
	{
		return \App\Models\Metadataschema::value($resourcetype);
	}

	static function resourcetypesList()
	{
		return \App\Models\Metadataschema::list('resourceType');
	}

	public function metadataValidate()
	{
		$msg = null;
		// At least one creator required
		if(count($this->creators)==0)
			$msg = $msg. "- Creators missing: At least one creator is required. <a href='" . route('tools.creators', $this->id) . "'>Fix it</a>\n";
		// At least one publisher required
		if(count($this->publishers)==0)
			$msg = $msg. "- Publishers missing: At least one publisher is required. <a href='" . route('tools.publishers', $this->id) . "'>Fix it</a>\n";
		// At least one rightsholder required
		if(count($this->rightsholders)==0)
			$msg = $msg. "- Rightsholders missing: At least one rightsholder is required. <a href='" . route('tools.rightsholders', $this->id) . "'>Fix it</a>\n";
		// Production year chronologically valid
		$py = $this->productionyear;
		if(strlen($py)==9) // AAAA-BBBB
			if(substr($py,0,4) >= substr($py,5,4)) // AAAA >= BBBB
				$msg = $msg. "- Production year '". $py . " invalid: the second year must be later that the first year. <a href='" . route('tools.edit', $this->id) . "'>Fix it</a>\n";
		
		return $msg;
	}
}
