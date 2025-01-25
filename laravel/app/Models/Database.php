<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

use App\Data\RadardatasetpureData;

class Database extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'description', 'radardataset',
        'productionYear', 'resource', 'rights', 'subjectAreas', 'publishers', 'creators',
        'user_id', '_token', '_method', 'submit'];

    // https://spatie.be/docs/laravel-data/v4/advanced-usage/eloquent-casting
    protected $casts = [
        'radardataset' => RadardatasetpureData::class,
    ];


    protected static function booted()
    {
        /*
        static::creating(function ($database) {
            $database->radardataset = new RadardatasetpureData;
        });
        // Update RADAR 'title' when database title updated.
		//jw:note This was causing a "Indirect modification of overloaded property App\Models\Database::$radardataset has no effect" exception, when modifying the database via a livewire component.
        static::updating(function ($database) {
            dd($database->radardataset);// = $database->title;
            $database->radardataset->descriptiveMetadata->title = $database->title;
            dd($database->radardataset);// = $database->title;
        });
         */

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

    //jw:note Specifying this relationship connects the column user_id with the User table
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function datasetdefs(): HasMany
    {
        return $this->hasMany(Datasetdef::class);
    }

    public function radardataset(): HasOne
    {
        return $this->hasOne(Radardataset::class);
    }

		public function creators()
    {
        return $this->hasMany(Creator::class); 
    }
}
