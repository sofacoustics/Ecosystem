<?php

namespace App\Models;

use App\Models\Datafile;
use App\Models\Service;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceLog extends Model
{
	public function datafile(): BelongsTo
	{
		return $this->belongsTo(Datafile::class);
	}

	public function service(): BelongsTo
	{
		return $this->belongsTo(Service::class);
	}
}
