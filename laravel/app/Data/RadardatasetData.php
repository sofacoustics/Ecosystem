<?php
/*
 *
 * laravel-data object for RADAR 'Dataset'
 *
 * See https://radar.products.fiz-karlsruhe.de/de/radarfeatures/radar-api
 */
namespace App\Data;

use Spatie\LaravelData\Data;
use App\Data\RadarcreatorData;
use App\Data\RadarpublisherData;
use App\Data\RadarsubjectareaData;
use App\Data\RadarresourcetypeData;

class RadardatasetData extends Data
{
    public function __construct(
        // mandatory fields
        public string $title,
        /** @var RadarcreatorData[] */
        public array $creators,
        /** @var RadarpublisherData[] */
        public array $publishers,
        public string $productionYear,
        /** @var RadarsubjectareaData[] */
        public array $subjectAreas,
        public RadarresourcetypeData $resource
        // optional fields
    ) {}
}
