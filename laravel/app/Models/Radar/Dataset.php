<?php
/*
 * A model to access/modify the RADAR dataset structure
 */

namespace App\Models\Radar;

use \App\Data\RadardatasetpureData;

class Dataset
{
    public \App\Data\RadardatasetpureData $data;

    function __construct(&$data)
    {
        $this->data = $data; // reference
    }

    public function setID($id)
    {
        $this->data = "$id";
    }


    ////////////////////////////////////////////////////////////////////////////////
    // creator
    //
    public function setCreatorType($key, $type)
    {
        $creatorArray = $this->data->descriptiveMetadata->creators->creator;
        if(array_key_exists($key, $creatorArray))
        {
            $creator = $creatorArray[$key];
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

    public function setCreatorToInstitution($key)
    {
        $creatorArray = $this->data->descriptiveMetadata->creators->creator;
        if(array_key_exists($key, $creatorArray))
        {
            $creator = $creatorArray[$key];
            $creator->givenName = "";
            $creator->familyName = "";
            $creator->creatorName = "";
        }
    }


}
