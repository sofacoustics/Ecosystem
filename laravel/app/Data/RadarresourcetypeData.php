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

use Carbon;

class RadarresourcetypeData extends Data
{
    // https://spatie.be/docs/laravel-data/v4/as-a-data-transfer-object/model-to-data-object
    #[MapInputName(SnakeCaseMapper::class)]
    public function __construct(
        // mandatory fields
        public string $resourceType,
        // optional fields
        public ?string $value= null,
        public Carbon\CarbonImmutable $createdAt,
        public Carbon\CarbonImmutable $updatedAt,
    ) {}
}
