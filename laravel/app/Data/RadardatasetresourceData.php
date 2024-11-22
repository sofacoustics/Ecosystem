<?php
/*
 *
 * laravel-data object for RADAR 'subjectArea'
 *
 * See https://radar.products.fiz-karlsruhe.de/de/radarfeatures/radar-api
 *
 *
 *                 "resource": {
                     "value": "Acoustics",
                     "resourceType": "OTHER"
*                  },
 */
namespace App\Data;

use Spatie\LaravelData\Data;

class RadardatasetresourceData extends RadarData
{
    // mandatory fields
    public string $resourceType;
    // optional fields
    public ?string $value= null;

    public function __construct(
        ) {}
}
