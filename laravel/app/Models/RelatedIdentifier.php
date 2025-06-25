<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\MorphTo;

class RelatedIdentifier extends Model
{
	use HasFactory;
	protected $fillable = ['id', 'relatedidentifierable_id', 'relatedidentifierable_type', ];
	
	public function relatedidentifierable(): MorphTo
	{
		return $this->morphTo(); 
	}

	static function isInternalLink($name)
	{
		if(str_contains($name, "ECOSYSTEM_DATABASE"))
			return 1;
		if(str_contains($name, "ECOSYSTEM_TOOL"))
			return 2;
		else
			return 0;
	}
	
	static function displayRelation($relationtype)
	{
		switch($relationtype)
		{
			case "-1":
				return "was used to create";
				break;
			case "-2":
				return "was created with";
				break;
			case "-3":
				return "can be processed by";
				break;
			default:
				return \App\Models\Metadataschema::display($relationtype);
		}
	}
	
	static function internalUrl($name)
	{
		switch(\App\Models\RelatedIdentifier::isInternalLink($name))
		{
			case 1: // database
				return route('databases.show',[ 'database' => substr($name, strlen("ECOSYSTEM_DATABASE")+1)]);
			case 2: // tool
				return route('tools.show',[ 'tool' => substr($name, strlen("ECOSYSTEM_TOOL")+1)]);
			default:
				return null;
		}
	}

	static function internalName($name)
	{
		switch(\App\Models\RelatedIdentifier::isInternalLink($name))
		{
			case 1: // database
				$database = \App\Models\Database::find(substr($name, strlen("ECOSYSTEM_DATABASE")+1));
				return $database->title." (".$database->productionyear.")";
			case 2: // tool
				$tool = \App\Models\Tool::find(substr($name, strlen("ECOSYSTEM_TOOL")+1));
				return $tool->title." (".$tool->productionyear.")";
			default:
				return null;
		}
	}

	static function externalUrl($type, $name)
	{
		switch(\App\Models\Metadataschema::display($type))
		{
			case "URL":
				return $name;
			case "arXiv":
				return "http://arxiv.org/abs/".$name;
			case "DOI":
				return "https://doi.org/".$name;
			case "IGSN":
				return "http://hdl.handle.net/10273/".$name;
			case "PMID":
				return "https://pubmed.ncbi.nlm.nih.gov/".$name;
			case "w3Id":
				return "https://w3id.org/".$name;
			default: // "ARK", "URN", "bibcode", "EAN13", "EISSN", "ePIC", "Handle", "ISBN", "ISSN", "ISTC", "LISSN", "LSID", "PURL", "UPC"
				return null;
		}
	}

}
