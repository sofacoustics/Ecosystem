<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Support\IdentifierUrl;

// Define the possible categories for the nameIdentifierSchemes
const nameIdentifierSchemeCategories = [
	'Other',
	'ORCID',
	'ROR' ];

// These are the values that RADAR accespts
const nameIdentifierSchemeCategoryValues = [
	'OTHER',
	'ORCID',
	'ROR' ];

const schemeURICategories = [
	'Other',
	'http://orcid.org/',
	'https://ror.org/' ];

/*
 * Base class for models which are associated directly with RADAR metadata
 */
class Radar extends Model
{
    public static function nameIdentifierScheme($x)
    {
        if($x == null) $x=0;
        if($x>2) $x=2;
        if($x<0) $x=0;
        return nameIdentifierSchemeCategories[$x];
    }

    public static function nameIdentifierSchemeValue($x)
    {
        if($x == null) $x=0;
        if($x>2) $x=2;
        if($x<0) $x=0;
        return nameIdentifierSchemeCategoryValues[$x];
    }


    /*
     * Detect if $value is a link to an ORCID, ROR, or DOI and extract the identifier (see App\Support\IdentifierUrl).
     * Returns ['scheme' => index into nameIdentifierSchemeCategories (0: Other, 1: ORCID, 2: ROR),
     *          'id' => identifier without the URL prefix,
     *          'schemeURI' => scheme URI for 'Other' (DOI), otherwise null]
     * or null if $value is not such a link.
     */
    public static function detectNameIdentifier($value)
    {
        $detected = IdentifierUrl::detect($value, ['ORCID', 'ROR', 'DOI']);
        if(!$detected)
            return null;
        switch($detected['type'])
        {
            case 'ORCID':
                return ['scheme' => 1, 'id' => $detected['id'], 'schemeURI' => null];
            case 'ROR':
                return ['scheme' => 2, 'id' => $detected['id'], 'schemeURI' => null];
            default: // DOI: there is no DOI scheme, use 'Other'
                return ['scheme' => 0, 'id' => $detected['id'], 'schemeURI' => IdentifierUrl::TYPES['DOI']['prefix']];
        }
    }

    /*
     * Strip the URL prefix from $value if it is a link of the given scheme (1: ORCID, 2: ROR).
     * Used where the scheme is fixed (e.g., Creator). Other values are returned unchanged.
     */
    public static function stripNameIdentifier($value, $scheme)
    {
        $detected = self::detectNameIdentifier($value);
        if($detected && $detected['scheme'] == $scheme)
            return $detected['id'];
        return $value;
    }

    public static function schemeURI($x)
    {
        if($x == null) $x=0;
        if($x>2) $x=2;
        if($x<0) $x=0;
        return schemeURICategories[$x];
    }
}
