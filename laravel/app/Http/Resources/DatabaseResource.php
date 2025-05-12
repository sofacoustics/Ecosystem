<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DatabaseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
		// https://g.co/gemini/share/bbe7b0990de9

		// special RADAR JSON variant here
		// e.g. an url with a "?format=radar" query
		if ($request->has('format') && $request->input('format') == 'radar')
        {
            $database = $this->resource; // get the database model, since using '$this->resource' retrieves the whole database model from the DatabaseResource rather than the 'resource' column.
			$data = [
				'id' => 'jw:todo the RADAR id',
				'parentId' => env("RADAR_WORKSPACE"),
				'descriptiveMetadata' => [
					'title' => $database->title,
					'creators' => [
						'creator' => CreatorResource::collection($this->whenLoaded('creators')),
					],
					'identifier' => [
						'value' => $database->doi,
						'identifierType' => 'DOI',
					],
					'productionYear' => $database->productionyear,
					'publicationYear' => $database->publicationyear,
					'publishers' => [
						'publisher' => PublisherResource::collection($this->whenLoaded('publishers')),
                    ],
                    'resource' => [
                        'value' => $database->resource,
                        'resourceType' => $database->resourcetypeDisplay($database->resourcetype),
                    ],
                    'rights' => [
                        'controlledRights' => $database->controlledRightsDisplay($database->controlledrights),
                        'additionalRights' => $database->additionalrights,
                    ],
                    'rightsHolders' => [
                        'rightsHolder' => RightsholderResource::collection($this->whenLoaded('rightsholders')),
                    ],
                    'subjectAreas' => [
                        'subjectArea' => SubjectAreaResource::collection($this->whenLoaded('subjectareas')),
                    ],
				],
			];
			return $data;
		}
		// otherwise standard Laravel JSON format
		else
			return parent::toArray($request);
    }
}
