<?php
/*
 * A model to access/modify the RADAR dataset publisher structure
 */

namespace App\Models\Radar\Dataset;

use \App\Data\RadardatasetpureData;
use \App\Data\RadardatasetnameidentifierData;

class Publisher extends \App\Data\RadardatasetpublisherData
{
    public \App\Data\RadardatasetpublisherData $data;

    public function __construct(&$data)
    {
        $this->data = $data; // reference
    }


    /*
     * Set the nameIdentifierScheme and reset all other values
     */
    public function setNameIdentifierScheme($nameIdentifierScheme)
    {
        if("$nameIdentifierScheme" == "ROR")
        {
            $this->data->nameIdentifierScheme = "ROR";
            $this->data->schemeURI = "https://ror.org/";
            $this->data->value = "";
            $this->data->nameIdentifier = "";
        }
        else if("$nameIdentifierScheme" == "ORCID")
        {
            $this->data->nameIdentifierScheme = "ORCID";
            $this->data->schemeURI = "https://orcid.org/";
            $this->data->value = "";
            $this->data->nameIdentifier = "";
        }
        else if("$nameIdentifierScheme" == "OTHER")
        {
            $this->data->nameIdentifierScheme = "OTHER";
            $this->data->schemeURI = null;
            $this->data->value = "";
            $this->data->nameIdentifier = null;
        }
        else
        {
            dd("This publisher does not have a valid nameIdentifierScheme value");
        }
    }
}

