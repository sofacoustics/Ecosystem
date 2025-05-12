<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

// Define the possible categories for the nameIdentifierSchemes
const nameIdentifierSchemeCategories = [
	'Other',
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

    public static function schemeURI($x)
    {
        if($x == null) $x=0;
        if($x>2) $x=2;
        if($x<0) $x=0;
        return schemeURICategories[$x];
    }
}
