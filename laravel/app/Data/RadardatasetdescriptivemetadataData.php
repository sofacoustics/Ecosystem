<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class RadardatasetdescriptivemetadataData extends RadarData
{
    #[Min(10)]
    public string $title;
    public string $productionYear;
    public RadardatasetpublishersData $publishers;
    public RadardatasetcreatorsData $creators;
    public RadardatasetsubjectareasData $subjectAreas;
    public RadardatasetresourceData $resource;
    public RadardatasetrightsData $rights;
    public RadardatasetrightsholdersData $rightsHolders;

    public function __construct(
      //
    ) {}

    /*
    public static function rules(): array
    {
        return [
            'title' => ['required', 'string', 'min:10'],
        ];
    }*/
}
