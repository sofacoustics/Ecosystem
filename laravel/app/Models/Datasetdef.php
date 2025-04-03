<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Datasetdef extends Model
{
    use HasFactory;

    protected $fillable = ['name','bulk_upload_filename_filter'];

    public function database(): BelongsTo
    {
        return $this->belongsTo(Database::class);
    }
    public function datafiletype(): BelongsTo
    {
        return $this->belongsTo(Datafiletype::class);
    }
    public function widget(): BelongsTo
    {
        return $this->belongsTo(Widget::class);
    }
}
