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
use App\Models\Radar\Metadataschema;


class Database extends Model
{
	use HasFactory;
	protected $fillable = ['title', 'additionaltitle', 'additionaltitletype', 'description', 'descriptiontype',
		'radardataset',
		'productionyear', 'publicationyear', 'language', 'resourcetype', 'resource', 'datasources','software',
		'processing', 'relatedinformation', 'controlledrights', 'additionalrights', 
		'creators', 'doi', 'visible', 'radarstatus',
		'bulk_upload_dataset_name_filter',
		'user_id', '_token', '_method', 'submit'];

	// https://spatie.be/docs/laravel-data/v4/advanced-usage/eloquent-casting
	protected $casts = [
		'radardataset' => RadardatasetpureData::class,
    ];

	protected static function booted()
    {
    }

    public function metadata()
    {
        return App\Models\Radar\Metadataschema::all();
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

	public function comments(): MorphMany
	{
		return $this->morphMany(Comment::class, 'commentable');
	}

	public function radardataset(): HasOne
	{
		return $this->hasOne(Radardataset::class);
	}

	static function resourcetypeDisplay($resourcetype)
    {
        return \App\Models\Radar\Metadataschema::display($resourcetype);
    }

    static function resourcetypeValue($resourcetype)
    {
        return \App\Models\Radar\Metadataschema::value($resourcetype);
    }

		static function resourcetypesList()
        {
            return \App\Models\Radar\Metadataschema::list('resourceType');
        }

		static function additionaltitletypeDisplay($additionaltitletype)
		{
            return \App\Models\Radar\Metadataschema::display($additionaltitletype);
		}

		static function additionaltitletypesList()
        {
            return \App\Models\Radar\Metadataschema::list('additionalTitleType');
		}

		static function descriptiontypeDisplay($descriptiontype)
		{
            return \App\Models\Radar\Metadataschema::display($descriptiontype);
		}

		static function descriptiontypesList()
        {
            return \App\Models\Radar\Metadataschema::list('descriptionType');
        }

		static function controlledrightsDisplay($controlledrights)
		{
            return \App\Models\Radar\Metadataschema::display($controlledrights);
		}

		static function controlledrightsValue($controlledrights)
		{
            return \App\Models\Radar\Metadataschema::value($controlledrights);
		}

		static function controlledrightsList()
        {
            return \App\Models\Radar\Metadataschema::list('controlledRights');
        }

		static function subjectareaDisplay($subjectareaindex)
		{
            return \App\Models\Radar\Metadataschema::display($subjectareaindex);
		}
		
		static function subjectareaValue($subjectareaindex)
		{
            return \App\Models\Radar\Metadataschema::value($subjectareaindex);
		}

		static function subjectareasList()
        {
            return \App\Models\Radar\Metadataschema::list('subjectArea');
        }

        public function getRadarJson() : String
        {
            $response = Http::get(env('APP_URL')."/api/databases/".$this->id."?format=radar");
            $jsonData = $response->json();
            return json_encode($jsonData);
        }
}
