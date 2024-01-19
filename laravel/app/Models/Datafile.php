<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Datafile extends Model
{
    use HasFactory;

    protected $fillable = [
        'id', 'name', 'dataset_id', 'datafiletype_id'
    ];

    public function dataset(): BelongsTo
    {
        return $this->belongsTo(Dataset::class);
		}

		public function datafiletype(): HasOne
		{
			return $this->hasOne(Datafiletype::class);
		}
}
