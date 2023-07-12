<?php

namespace App\Models;

use App\Events\ChirpCreated;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Chirp extends Model
{
	use HasFactory;

	// enable mass-assignment for the 'message' attribute (https://bootcamp.laravel.com/blade/creating-chirps>
	protected $fillable = [
		'message',
	];

	protected $dispatchesEvents = [
		'created' => ChirpCreated::class,
	];

	public function user(): BelongsTo
	{
		return $this->belongsTo(User::class);
	}
}
