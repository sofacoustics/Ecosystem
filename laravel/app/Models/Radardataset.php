<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;


/*
 * A RADAR dataset is what we in SONICOM call a 'database'
 *
 */

class Radardataset extends Model
{
    use HasFactory;

    public function database(): BelongsTo
    {
        return $this->belongsTo(Database::class);
    }

    // called 'resource' so that RadardatasetData can load '$resource'.
    public function resource(): HasOne
    {
        return $this->hasOne(Radardatasetresourcetype::class);
    }

    public function radardatasetresourcetype(): HasOne
    {
        return $this->hasOne(Radardatasetresourcetype::class);
    }

    //jw:note called 'subject_areas' since RadardatasetData property called 'subjectAreas'
    public function subject_areas(): HasMany
    {
        return $this->hasMany(Radardatasetsubjectarea::class);
    }

    public function radardatasetsubjectarea(): HasOne
    {
        return $this->hasOne(Radardatasetsubjectarea::class);
    }

    public function radardatasetsubjectareas(): HasMany
    {
        return $this->hasMany(Radardatasetsubjectarea::class);
    }


}
