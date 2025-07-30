<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;

use Illuminate\Support\Facades\Http;

use App\Data\RadardatasetpureData;
use App\Models\Metadataschema;


class Database extends Model
{
	use HasFactory;
	protected $fillable = ['title', 'additionaltitle', 'additionaltitletype', 'descriptiongeneral', 
		'radardataset',
		'productionyear', 'publicationyear', 'language', 'resourcetype', 'resource', 'datasources','software',
		'processing', 'controlledrights', 'additionalrights', 
		'creators', 'doi', 'visible', 'radar_status',
		'bulk_upload_dataset_name_filter',
		'user_id', '_token', '_method', 'submit'];

	// https://spatie.be/docs/laravel-data/v4/advanced-usage/eloquent-casting
	/*protected $casts = [
		'radardataset' => RadardatasetpureData::class,
    ];*/

	protected static function booted()
	{
	}

	public function metadata()
	{
		return App\Models\Metadataschema::all();
	}

	//
	// RELATIONSHIPS
	//
	/**
	 * Get the datasets for a database
	 *
	 * https://laravel.com/docs/10.x/eloquent-relationships#one-to-many
	 */
	public function datasets(): HasMany
	{
		return $this->hasMany(Dataset::class);
		}

	public function user(): BelongsTo
	{
		return $this->belongsTo(User::class); // Specifying this relationship connects the column user_id with the User table
	}

	public function datasetdefs(): HasMany
	{
		return $this->hasMany(Datasetdef::class);
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

	public function comments(): MorphMany
	{
		return $this->morphMany(Comment::class, 'commentable');
	}

	public function radardataset(): HasOne
	{
		return $this->hasOne(Radardataset::class);
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
		if($resourcetype == null)
			return "Dataset";
		else
			return \App\Models\Metadataschema::display($resourcetype);
	}

	static function resourcetypeValue($resourcetype)
	{
		if($resourcetype == null)
			return "DATASET";
		else
			return \App\Models\Metadataschema::value($resourcetype);
	}

	public function getRadarJson() : String
	{
		$response = Http::get(env('APP_URL')."/api/databases/".$this->id."?format=radar");
		$jsonData = $response->json();
		return json_encode($jsonData);
	}

	public function metadataValidate()
	{
		$msg = null;
		// At least one creator required
		if(count($this->creators)==0)
			$msg = $msg. "- Creators missing: At least one creator is required. <a href='" . route('databases.creators', $this->id) . "'>Fix it</a>\n";
		// At least one publisher required
		if(count($this->publishers)==0)
			$msg = $msg. "- Publishers missing: At least one publisher is required. <a href='" . route('databases.publishers', $this->id) . "'>Fix it</a>\n";
		// At least one rightsholder required
		if(count($this->rightsholders)==0)
			$msg = $msg. "- Rightsholders missing: At least one rightsholder is required. <a href='" . route('databases.rightsholders', $this->id) . "'>Fix it</a>\n";
		// Production year chronologically valid
		$py = $this->productionyear;
		if(strlen($py)==9) // AAAA-BBBB
			if(substr($py,0,4) >= substr($py,5,4)) // AAAA >= BBBB
				$msg = $msg. "- Production year '". $py . " invalid: the second year must be later that the first year. <a href='" . route('databases.edit', $this->id) . "'>Fix it</a>\n";
		
		return $msg;
	}

	/*
	 * Check that database is in a state where it is ready to persistently publish
	 */
	public function isReadyToPublish(&$message)
	{
		foreach($this->datasets as $dataset)
		{
			// check dataset is ready to publish
			$missing = $dataset->missing_datafiles();
			if(count($missing))
			{
				$message .= "\nThe dataset ";
				$message .= $dataset->link();
				$message .= " is missing " . count($missing) . " datafile";
				if(count($missing)>1)
				   	$message .= 's';
			   	$message .= "!";
			}
		}
		if($message != '')
			return false;
		return true;
	}

	/*
	 * Reset all RADAR IDs and releated fields
	 */
	public function resetRADAR()
	{
		// set dataset and datafile radar_ids back to null
		foreach($this->datasets as $dataset) // iterate through all Dataset of the Database
		{
			foreach($dataset->datafiles as $datafile)
			{
				$datafile->radar_id = null;
				$datafile->datasetdef_radar_id = null;
				$datafile->datasetdef_radar_upload_url = null;
				$datafile->save();
			}
			$dataset->radar_id = null;
			$dataset->radar_upload_url = null;
			$dataset->save();
		}
		$this->radar_id = null;
		$this->doi = null;
		$this->radar_status = null;
		$this->save();
	}
	
	public function citation() : String
	{
		$str = '';
		if(count($this->creators)==0)
			$str = 'Anonymous'; 
		else
			foreach($this->creators as $index => $creator)
			{
				if($creator->familyName)
				{		// Person
					if($creator->givenName)
						$seq = $creator->familyName . ', ' . $creator->givenName[0] . '.';
					else
						$seq = $creator->familyName; 
				}
				else
				{		// Institution
					$seq = $creator->creatorName; 
				}
				if($index == 0)
					$str = $seq;
				elseif($index == count($this->creators)-1)
					$str = $str. ', and ' . $seq; 
				else
					$str = $str. ', ' . $seq; 
			}
		if($this->publicationyear != 'unknown' & $this->publicationyear != null)
			$py = $this->publicationyear; // publication year available and not unknown -> use it!
		else
			$py = date("Y",strtotime($this->updated_at)); // publication year unknown --> the last update of the database is used
			
			
		$str = $str . ' (<b>' . $py . '</b>). ';
		$str = $str . '"' . $this->title . '", <i>The SONICOM Ecosystem: Database #' . $this->id . '</i>.'; 
		$str = $str . ' URL: ' . route('databases.show', $this->id) . '.'; 
		return $str;
	}
	
	public function citationtooltip() : String
	{
		if($this->publicationyear != 'unknown' & $this->publicationyear != null)
			return 'Date is the publication date in the Datathek.';
		else
			return 'The Datathek publication year is not available, thus, the date is the year of the last database update.'; // publication year unknown --> the last update of the database is used
	}
}
