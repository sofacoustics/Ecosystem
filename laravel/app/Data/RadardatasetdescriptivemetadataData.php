<?php

namespace App\Data;

use Livewire\Wireable;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Concerns\WireableData;

class RadardatasetdescriptivemetadataData extends Data implements Wireable
{
    use WireableData;

    #[Min(10)]
    public string $title;
    public string $productionYear;
    /** @var RadardatasetpublishersData[] */
    public RadardatasetpublishersData $publishers;
    /** @var RadardatasetcreatorsData[] */
    public RadardatasetcreatorsData $creators;
//    /** @var RadardatasetsubjectareasData[] */
//    public RadardatasetsubjectareasData $subjectAreas;
    public RadardatasetresourceData $resource;
//    public RadardatasetrightsData $rights;
//    public RadardatasetrightsholdersData $rightsHolders;

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
