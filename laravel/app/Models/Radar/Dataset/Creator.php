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

    public function setCreatorType($type)
    {
        $creator = $this->data;
        if($this->type() == "$type")
            return; // don't reset if we're already this type
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

    public function hasAffiliation() : bool
    {
        if($this->data->creatorAffiliation != null)
            return true;
        else
            return false;
    }

	// since we're not showing 'creatorName' for people, we have to set it here ourselves.
	public function updateCreatorName()
	{
		if($this->type() == "person")
		{
			$this->data->creatorName = $this->data->familyName . ", " . $this->data->givenName;
		}
		else
		{
			$this->data->familyName = null;
			$this->data->givenName = null;
		}
	}

/*
    public function addAffiliation($value)
    {
        $this->data->creatorAffiliation = \App\Data\RadardatasetcreatoraffiliationData:from(['value' => "$value");
    }
 */
}
