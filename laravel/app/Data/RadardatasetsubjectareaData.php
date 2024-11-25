<?php
/*
 *
 * laravel-data object for RADAR 'subjectArea'
 *
 * See https://radar.products.fiz-karlsruhe.de/de/radarfeatures/radar-api
 */
namespace App\Data;

use Spatie\LaravelData\Data;

class RadardatasetsubjectareaData extends RadarData
{
    // mandatory fields
    public string $controlledSubjectAreaName;
    //public string $additionalSubjectAreaName;
    // optional fields
    public ?string $additionalSubjectAreaName = null;

    public function __construct(
        ) {}
}
