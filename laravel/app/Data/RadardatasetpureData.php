<?php
/*
 *
 * laravel-data object for RADAR 'Dataset'
 *
 * THIS ONE IS TO BE SAVED IN A JSON FIELD IN THE DATABASE
 *
 * See https://radar.products.fiz-karlsruhe.de/de/radarfeatures/radar-api
 */
namespace App\Data;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\LoadRelation;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

use App\Data\RadarcreatorData;
use App\Data\RadardatasetpublisherData;
use App\Data\RadardatasetsubjectareaData;
use App\Data\RadardatasetresourcetypeData;

use App\Models\Radardataset;

class RadardatasetpureData extends Data
{
        //jw:note Don't specify variables in the constructor, otherwise
        //jw:note instantiating from a model doesn't work (wrong parameter count).
        // mandatory fields
        public string $title;
        /** @var RadardatasetpublishersData[] */
        public RadardatasetpublishersData $publishers;
        /** @var RadarcreatorData[] */
        //public array $creators,
        /** @var RadarpublisherData[] */
        //public array $publishers,

        //public string $productionYear,
        /** @var RadardatasetsubjectareaData[] */
        //public array $subjectAreas;
        //public RadardatasetresourcetypeData $resource;
        // optional fields
        //
        //public RadardatasetpublishersData $publishers;
//    public function __construct(
 //   ) {}

    /*
    public static function fromModel(Radardataset $radardataset): self
    {
        return new self("$radardataset->title", "$radardataset->radardatasetsubjectarea", "$radardataset->radardatasetresearchtype" );
    }
     */

/*    public static function normalizers(): array
    {
        return [
            Spatie\LaravelData\Normalizers\JsonNormalizer::class,
        ];
}*/
}
