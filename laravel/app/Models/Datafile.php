<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Storage;

class Datafile extends Model
{
    use HasFactory;

    protected $fillable = [
        'id', 'name', 'location', 'dataset_id', 'datasetdef_id'
    ];

    public function dataset(): BelongsTo
    {
        return $this->belongsTo(Dataset::class);
    }

    public function datasetdef(): BelongsTo
    {
        /*
        $datafile_id = $this->id;
        $dataset = $this->dataset;
        $dataset_id = $dataset->id;
        $database = $dataset->database;
        $database_id = $database->id;
        $database_name = $database->name;
        if($this->dataset != null)
            return $this->belongsTo(Datasetdef::class)->where('database_id', $this->dataset->database->id);
        */
        return $this->belongsTo(Datasetdef::class);
    }

    public function path()
    {
        return $this->dataset->database()->get()->value('id')."/".$this->dataset()->get()->value('id')."/".$this->id;
    }

    public function localpath()
    {
        $path = Storage::disk('local')->get($this->path() . '/' . $this->name);
        return $path;
    }
}
