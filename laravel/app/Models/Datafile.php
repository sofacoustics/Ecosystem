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

    /*
        Return the directory containing this file
    */
    public function directory()
    {
        return $this->dataset->database()->get()->value('id')."/".$this->dataset()->get()->value('id')."/".$this->id;
    }

    public function path()
    {
        return $this->directory() . "/" . $this->name;
    }

    public function localpath()
    {
        $path = $this->path();
        $name = $this->name;
        $localpath = Storage::disk('local')->get($path);
        return $path;
    }

    /*
        Return the absolute path to the data file
    */
    public function absolutepath()
    {
        $path = $this->path();
        $name = $this->name;
        $absolutepath = Storage::path('public/'.$path);
        return $absolutepath;
    }

    public function url()
    {
        return Storage::url($this->path());
    }

    /**
     * Return the publicly available asset we can then use in HTML code.
     * 
     * $suffix: A suffix to add to the asset (since this appears to be difficult to do in livewire syntax)
     */
    public function asset($suffix = "")
    {
        $absolutepath = $this->absolutepath().$suffix;
        // check asset exists
        if(file_exists($this->absolutepath().$suffix))
            return asset($this->url().$suffix);
        else
            return "";
    }

    public function isImage()
    {
        if($this->datasetdef->datafiletype->id == 1)
            return true;
        return false;

    }
}
