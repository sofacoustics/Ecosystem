<?php
/*
 *
 * laravel-data object for RADAR 'Dataset'
 *
 * See https://radar.products.fiz-karlsruhe.de/de/radarfeatures/radar-api
 */
namespace App\Data;

use Spatie\LaravelData\Data;
use App\Data\Radar\CreatorData;

class DatasetData extends Data
{
    public function __construct(
        // mandatory fields
        public string $id,
        public string $title,
        public string $descriptions,
        /** @var CreatorData[] */
        //public array $creators,
    ) {}
}
