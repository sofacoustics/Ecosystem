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

    /**
     * Get the files for a dataset
     * 
     * https://laravel.com/docs/10.x/eloquent-relationships#one-to-many
     */
    public function datafiles(): HasMany
    {
        return $this->hasMany(Datafile::class);
    }

		//jw:note This creates the database->dataset relationship using standard column names
    public function database(): BelongsTo
    {
        return $this->belongsTo(Database::class);
    }
}
