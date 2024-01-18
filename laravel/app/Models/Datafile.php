<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Datafile extends Model
{
    use HasFactory;

    protected $fillable = [
        'id', 'name', 'dataset_id'
    ];

    public function dataset(): BelongsTo
    {
        return $this->belongsTo(Dataset::class);
    }
}
