<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

use App\Http\Resources\RadarRelatedIdentifierResourceCollection;

class RadarToolResource extends JsonResource
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
		$tool = $this->resource; // get the tool model, since using '$this->resource' retrieves the whole tool model from the ToolResource rather than the 'resource' column.

		$array =
		[
			'id' => $tool->radar_id,
			'parentId' => config('services.radar.workspace'),
			'technicalMetadata' =>
			[
				'schema' => [
					'key' => 'RDDM',
					'version' => '9.1',
				]
			],
		];
		
		$cr = \App\Models\Metadataschema::value($tool->controlledrights);
		if(str_contains($cr, 'ECOSYSTEM'))
		{
			$cr = 'OTHER';
			$ar = \App\Models\Metadataschema::display($tool->controlledrights);
		}
		else
		{
			$ar = $tool->additionalrights;
		}
		
		$descriptiveMetadata = 
		[
			'title' => $tool->title . " (Tool #" . $tool->id . ")",
			'creators' => [
				'creator' => RadarCreatorResource::collection($this->whenLoaded('creators')),
			],
			'identifier' => [
				'value' => $tool->doi,
				'identifierType' => 'DOI',
			],
			'productionYear' => $tool->productionyear,
			'publicationYear' => $tool->publicationyear,
			'publishers' => [
				'publisher' => RadarPublisherResource::collection($this->whenLoaded('publishers')),
			],
			'resource' => [
				'value' => $tool->resource,
				'resourceType' => $tool->resourcetypeValue($tool->resourcetype),
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
				'relatedIdentifier' => new RadarRelatedIdentifierResourceCollection($this->whenLoaded('relatedidentifiers'), $tool),
			],
		];
		$array['descriptiveMetadata'] = $descriptiveMetadata;
		return $array; 
	}
}
