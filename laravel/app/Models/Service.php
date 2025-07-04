<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Service extends Model
{
	use HasFactory;

	public function logs(): HasMany
	{
		return $this->hasMany(ServiceLog::class);
	}

	public function latestLog(): HasOne
	{
		    return $this->logs()->one()->latestOfMany();
	}
}
