<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dataset extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'id', 'name', 'database_id'
    ];    

    /**
     * Get the files for a dataset
     * 
     * https://laravel.com/docs/10.x/eloquent-relationships#one-to-many
     */
    public function files(): HasMany
    {
        return $this->hasMany(File::class);
    }

}
