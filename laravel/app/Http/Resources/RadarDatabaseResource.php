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
                ],
                'responsibleEmail' => $database->user->email
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
			// Expand YYYY- to YYYY-currentyear
		$py = $database->productionyear;
		if(strlen($py) == 5) // YYYY-
			if(date("Y") != substr($py,0,4)) 
				$py = $py.date("Y"); // YYYY is not currentyear --> append the current year
			else
				$py = substr($py,0,4); // YYYY is the current year, remove the dash
		
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
			'productionYear' => $py,
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
