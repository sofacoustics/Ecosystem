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

    protected static function boot()
    {
        parent::boot();
        static::deleting(function($model) {
            $directory = $model->directory();
            //dd($model->directory());
            //dd("deleting datafile $directory");
            //$model->datafiles->each->delete();
            Storage::disk('public')->deleteDirectory($directory);
        });
    }

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
    public function directory() : string
    {
        $database_id = $this->dataset->database->id;
        $dataset_id = $this->dataset->id;
        $datafile_id = $this->id;
        $directory = $database_id."/".$dataset_id."/".$datafile_id;
        //dd($directory);
        //return $this->dataset->database()->get()->value('id')."/".$this->dataset()->get()->value('id')."/".$this->id;
        return "$database_id/$dataset_id/$datafile_id";
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
        $pathwithsuffix = $this->absolutepath().$suffix;
        // check asset exists and return.
        // Add a file time modification query string to force reload, if file has changed
        if(file_exists($pathwithsuffix))
            return asset($this->url().$suffix.'?'.filemtime($pathwithsuffix));
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
