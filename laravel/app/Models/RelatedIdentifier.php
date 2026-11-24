<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\MorphTo;

use App\Support\IdentifierUrl;

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
	
	static function valueRelation($relationtype)
	{
		switch($relationtype)
		{
			case "-1":
				return "COMPILES";
				break;
			case "-2":
				return "IS_COMPILED_BY";
				break;
			case "-3":
				return "IS_CONTINUED_BY";
				break;
			default:
				return \App\Models\Metadataschema::value($relationtype);
		}
	}
	static function internalUrl($name, $defaultUrl = null)
	{
		switch(\App\Models\RelatedIdentifier::isInternalLink($name))
		{
			case 1: // database
				return route('databases.show',[ 'database' => substr($name, strlen("ECOSYSTEM_DATABASE")+1)]);
			case 2: // tool
				return route('tools.show',[ 'tool' => substr($name, strlen("ECOSYSTEM_TOOL")+1)]);
			default:
				return $defaultUrl;
		}
	}

	static function internalName($name, $defaultname = null)
	{
		switch(\App\Models\RelatedIdentifier::isInternalLink($name))
		{
			case 1: // database
				$database = \App\Models\Database::find(substr($name, strlen("ECOSYSTEM_DATABASE")+1));
				if ($database == null)
					return null;
				else
					return $database->title." (".$database->productionyear.")";
			case 2: // tool
				$tool = \App\Models\Tool::find(substr($name, strlen("ECOSYSTEM_TOOL")+1));
				if ($tool == null)
					return null;
				else
					return $tool->title." (".$tool->productionyear.")";
			default:
				return $defaultname;
		}
	}

	/*
	 * Identifier types which are expanded to a link (see App\Support\IdentifierUrl).
	 * Values: display names of the relatedIdentifierType (Metadataschema).
	 * ROR and ORCID are not relatedIdentifierTypes in RADAR, thus such links stay URLs.
	 */
	const URL_TYPES = ['arXiv', 'DOI', 'IGSN', 'PMID', 'w3Id'];

	static function externalUrl($type, $name)
	{
		$display = \App\Models\Metadataschema::display($type);
		if($display === "URL")
			return $name;
		if(in_array($display, self::URL_TYPES, true))
			return IdentifierUrl::url($display, $name);
		// "ARK", "URN", "bibcode", "EAN13", "EISSN", "ePIC", "Handle", "ISBN", "ISSN", "ISTC", "LISSN", "LSID", "PURL", "UPC"
		return null;
	}

	/*
	 * Detect if $name is a link of an identifier type which we can expand (see URL_TYPES).
	 * Returns ['type' => Metadataschema id of the relatedIdentifierType, 'name' => identifier without the URL prefix],
	 * or null if not detected.
	 */
	static function detectExternalType($name)
	{
		$detected = IdentifierUrl::detect($name, self::URL_TYPES);
		if(!$detected)
			return null;
		$type = \App\Models\Metadataschema::where('name', 'relatedIdentifierType')->where('display', $detected['type'])->value('id');
		if($type === null)
			return null;
		return ['type' => $type, 'name' => $detected['id']];
	}

}
