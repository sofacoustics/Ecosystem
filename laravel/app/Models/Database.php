<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Database extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'description', 'user_id'];

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
}
