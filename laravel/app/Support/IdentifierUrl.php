<?php

namespace App\Support;

/*
 * Known identifiers which can be written as a link (e.g., https://doi.org/10.1000/xyz).
 * Single place to
 *  - build the link from an identifier (url), and
 *  - detect a pasted link and extract the identifier (detect).
 * The mapping to the scheme/type numbers is done by the users of this class
 * (Radar for ORCID/ROR/DOI in Creator/Publisher/Rightsholder, RelatedIdentifier for relations).
 */
class IdentifierUrl
{
	/*
	 * Key: identifier type (for related identifiers: the display name of the relatedIdentifierType)
	 *   'prefix':  prepended to the identifier to build the link
	 *   'pattern': regex matching a link, the identifier is captured as 'id'
	 */
	const TYPES = [
		'ORCID' => ['prefix' => 'https://orcid.org/',               'pattern' => '#^https?://(www\.)?orcid\.org/(?<id>[^/?\#]+)/?$#i'],
		'ROR'   => ['prefix' => 'https://ror.org/',                 'pattern' => '#^https?://(www\.)?ror\.org/(?<id>[^/?\#]+)/?$#i'],
		'DOI'   => ['prefix' => 'https://doi.org/',                 'pattern' => '#^https?://(www\.|dx\.)?doi\.org/(?<id>.+)$#i'],
		'arXiv' => ['prefix' => 'http://arxiv.org/abs/',            'pattern' => '#^https?://(www\.)?arxiv\.org/abs/(?<id>.+)$#i'],
		'IGSN'  => ['prefix' => 'http://hdl.handle.net/10273/',     'pattern' => '#^https?://(www\.)?hdl\.handle\.net/10273/(?<id>.+)$#i'],
		'PMID'  => ['prefix' => 'https://pubmed.ncbi.nlm.nih.gov/', 'pattern' => '#^https?://(www\.)?pubmed\.ncbi\.nlm\.nih\.gov/(?<id>[^/?\#]+)/?$#i'],
		'w3Id'  => ['prefix' => 'https://w3id.org/',                'pattern' => '#^https?://(www\.)?w3id\.org/(?<id>.+)$#i'],
	];

	/*
	 * Build the link for an identifier of the given type. Returns null for unknown types.
	 */
	public static function url($type, $id)
	{
		if(!isset(self::TYPES[$type]))
			return null;
		return self::TYPES[$type]['prefix'].$id;
	}

	/*
	 * Detect if $value is a link of one of the known types.
	 * $types: restrict the detection to these types (null: all types).
	 * Returns ['type' => key of TYPES, 'id' => identifier without the URL prefix], or null if not detected.
	 */
	public static function detect($value, ?array $types = null)
	{
		$value = trim((string) $value);
		foreach(self::TYPES as $type => $t)
		{
			if($types !== null && !in_array($type, $types, true))
				continue;
			if(preg_match($t['pattern'], $value, $matches))
				return ['type' => $type, 'id' => $matches['id']];
		}
		return null;
	}
}
