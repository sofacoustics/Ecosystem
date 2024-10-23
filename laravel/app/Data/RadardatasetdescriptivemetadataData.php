<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class RadardatasetdescriptivemetadataData extends Data
{
    public string $title;
    public string $productionYear;
    /** @var RadardatasetpublishersData[] */
    public RadardatasetpublishersData $publishers;
    /** @var RadardatasetcreatorsData[] */
    public RadardatasetcreatorsData $creators;
    /** @var RadardatasetsubjectareasData[] */
    public RadardatasetsubjectareasData $subjectAreas;
    public RadardatasetresourceData $resource;
    public RadardatasetrightsData $rights;
    public RadardatasetrightsholdersData $rightsHolders;

    public function __construct(
      //
    ) {}
}
