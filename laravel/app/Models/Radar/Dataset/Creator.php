<?php
/*
 * A model to access/modify the RADAR dataset creator structure
 */

namespace App\Models\Radar\Dataset;

use \App\Data\RadardatasetpureData;

class Creator
{
    public \App\Data\RadardatasetcreatorData $data;

    function __construct(&$data)
    {
        $this->data = $data; // reference
    }

    public function type()
    {
        if($this->data->givenName === null && $this->data->familyName == null)
            return "institution";
        else
            return "person";
    }


    ////////////////////////////////////////////////////////////////////////////////
    // creator
    //
    public function setCreatorType($type)
    {
        $creator = $this->data;
        $creator->creatorName = "";
        if("$type" === "person")
        {
            $creator->givenName = "";
            $creator->familyName = "";
        }
        else if("$type" === "institution")
        {
            $creator->givenName = null;
            $creator->familyName = null;
        }
    }
}
