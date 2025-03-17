<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

use App\Data\RadardatasetpureData;
use App\Models\Radar\Metadataschema;

class Database extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'additionaltitle', 'additionaltitletype', 'description', 'descriptiontype',
		    'radardataset',
        'productionyear', 'publicationyear', 'language', 'resourcetype', 'resource', 'datasources','software', 
				'processing', 'relatedinformation', 'controlledrights', 'additionalrights', //'subjectAreas', 'publishers', 
				'creators',
        'user_id', '_token', '_method', 'submit'];

    // https://spatie.be/docs/laravel-data/v4/advanced-usage/eloquent-casting
    protected $casts = [
        'radardataset' => RadardatasetpureData::class,
    ];


    protected static function booted()
    {
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
        return $this->belongsTo(User::class); //jw:note Specifying this relationship connects the column user_id with the User table
    }

    public function datasetdefs(): HasMany
    {
        return $this->hasMany(Datasetdef::class);
    }

		public function creators()
    {
        return $this->hasMany(Creator::class); 
    }

		public function publishers()
    {
        return $this->hasMany(Publisher::class); 
    }
		
		public function subjectareas()
    {
        return $this->hasMany(SubjectArea::class); 
    }

		public function rightsholders()
    {
        return $this->hasMany(Rightsholder::class); 
    }

		public function keywords()
    {
        return $this->hasMany(Keyword::class); 
    }

		public function comments()
    {
        return $this->hasMany(Comment::class); 
    }

    public function radardataset(): HasOne
    {
        return $this->hasOne(Radardataset::class);
    }

		static function resourcetypeDisplay($resourcetype)
		{
			$result = \App\Models\Radar\Metadataschema::where('id', $resourcetype)->select('display')->get();
			return $result[0]->attributes['display'];
		}
		
		static function resourcetypesList()
    {
        $rr = \App\Models\Radar\Metadataschema::where('name','resourceType')->select('display')->get();
				return $rr;
    }
		static function additionaltitletypeDisplay($additionaltitletype)
		{
			$result = \App\Models\Radar\Metadataschema::where('id', $additionaltitletype)->select('display')->get();
			return $result[0]->attributes['display'];
		}
		
		static function additionaltitletypesList()
    {
        $rr = \App\Models\Radar\Metadataschema::where('name','additionalTitleType')->select('display')->get();
				return $rr;
    }
		
		static function descriptiontypeDisplay($descriptiontype)
		{
			$result = \App\Models\Radar\Metadataschema::where('id', $descriptiontype)->select('display')->get();
			return $result[0]->attributes['display'];
		}
		
		static function descriptiontypesList()
    {
        $rr = \App\Models\Radar\Metadataschema::where('name','descriptionType')->select('display')->get();
				return $rr;
    }

		static function controlledrightsDisplay($controlledrights)
		{
			$result = \App\Models\Radar\Metadataschema::where('id', $controlledrights)->select('display')->get();
			return $result[0]->attributes['display'];
		}
		
		static function controlledrightsList()
    {
        $rr = \App\Models\Radar\Metadataschema::where('name','controlledRights')->select('display')->get();
				return $rr;
    }

		static function subjectareaDisplay($subjectareaindex)
		{
			$result = \App\Models\Radar\Metadataschema::where('id', $subjectareaindex)->select('display')->get();
			return $result[0]->attributes['display'];
		}
		
		static function subjectareasList()
    {
        $rr = \App\Models\Radar\Metadataschema::where('name','subjectArea')->select('display')->get();
				return $rr;
    }
}
