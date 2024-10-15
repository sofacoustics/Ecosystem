<?php
/*
 *
 * laravel-data object for RADAR 'Publisher'
 *
 * See https://radar.products.fiz-karlsruhe.de/de/radarfeatures/radar-api
 */
namespace App\Data;

use Spatie\LaravelData\Data;

class RadarpublisherData extends Data
{
    public function __construct(
        // mandatory fields
        public string $name,
        // optional
        public ?string $orcidid = null,
        public ?string $rorid = null,
    ) {}
}
