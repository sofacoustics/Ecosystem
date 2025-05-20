<?php

namespace App\Models\Radar;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Metadataschema extends Model
{
    use HasFactory;

    /*
     * Lists all rows for a specific 'name' in their 'display' format
     */
    static function list($name)
    {
        $rr = Metadataschema::where('name',$name)->select('display')->get();
        return $rr;
    }

    /*
     * Return the 'value' field for this id. This is necessary for valid RADAR JSON
     */
    static function value($id)
    {
        $result = Metadataschema::where('id', $id)->select('value')->get();
        return $result[0]->attributes['value'];
    }

    /*
     * Return the 'display' value for this id. This is something the user will
     * find easier to read than the 'value'.
     */
    static function display($id)
    {
        $result = Metadataschema::where('id', $id)->select('display')->get();
        return $result[0]->attributes['display'];
    }
}
