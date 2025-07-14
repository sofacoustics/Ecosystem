<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

use App\Http\Resources\RadarRelatedIdentifierResourceCollection;

class RadarDatabaseResource extends JsonResource
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
		$database = $this->resource; // get the database model, since using '$this->resource' retrieves the whole database model from the DatabaseResource rather than the 'resource' column.
		$array =
		[
			'id' => $database->radar_id,
			'parentId' => config('services.radar.workspace'),
			'technicalMetadata' => 
			[
				'schema' => [
					'key' => 'RDDM',
					'version' => '9.1',
                ]
			],
		];

		$cr = \App\Models\Metadataschema::value($database->controlledrights);
		if(str_contains($cr, 'ECOSYSTEM'))
		{
			$cr = 'OTHER';
			$ar = \App\Models\Metadataschema::display($database->controlledrights);
		}
		else
		{
			$ar = $database->additionalrights;
		}

		$descriptiveMetadata =
		[
			'title' => $database->title . " (Database #" . $database->id . ")",
			'creators' => [
				'creator' => RadarCreatorResource::collection($this->whenLoaded('creators')),
			],
			'identifier' => [
				'value' => $database->doi,
				'identifierType' => 'DOI',
			],
			'productionYear' => $database->productionyear,
			'publicationYear' => $database->publicationyear,
			'publishers' => [
				'publisher' => RadarPublisherResource::collection($this->whenLoaded('publishers')),
			],
			'resource' => [
				'value' => $database->resource,
				'resourceType' => $database->resourcetypeValue($database->resourcetype),
			],
			'rights' => [
				'controlledRights' => $cr,
				'additionalRights' => $ar,
			],
			'rightsHolders' => [
				'rightsHolder' => RadarRightsholderResource::collection($this->whenLoaded('rightsholders')),
			],
			'subjectAreas' => [
				'subjectArea' => RadarSubjectAreaResource::collection($this->whenLoaded('subjectareas')),
			],
			'relatedIdentifiers' => [
				'relatedIdentifier' => new RadarRelatedIdentifierResourceCollection($this->whenLoaded('relatedidentifiers'), $database),
			],
		];
		$array['descriptiveMetadata'] = $descriptiveMetadata;
		return $array; 
	}
}
