<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Models\Service;

class Widget extends Model
{
    use HasFactory;

    protected $fillable = [
        'id', 'name', 'description'
    ];

    public function service(): belongsTo
    {
        return $this->belongsto(Service::class);
    }
}
