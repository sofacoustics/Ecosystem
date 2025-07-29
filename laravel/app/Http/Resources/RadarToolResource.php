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
				],
                'responsibleEmail' => $tool->user->email
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
		
			// Expand YYYY- to YYYY-currentyear
		$py = $tool->productionyear;
		if(strlen($py) == 5) // YYYY-
			if(date("Y") != substr($py,0,4)) 
				$py = $py.date("Y"); // YYYY is not currentyear --> append the current year
			else
				$py = substr($py,0,4); // YYYY is the current year, remove the dash

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
			'productionYear' => $py,
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
