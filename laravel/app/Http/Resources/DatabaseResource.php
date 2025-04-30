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
			$data = [
				'id' => 'jw:todo the RADAR id',
				'parentId' => env("RADAR_WORKSPACE"),
				'descriptiveMetadata' => [
					'title' => $this->title,
					'creators' => [
						'creator' => CreatorResource::collection($this->whenLoaded('creators')),
					],
					'identifier' => [
						'value' => $this->doi,
						'identifierType' => 'DOI',
					],
					'productionYear' => $this->productionyear,
					'publicationYear' => $this->publicationyear,
				],
			];
			return $data;
		}
		// otherwise standard Laravel JSON format
		else
			return parent::toArray($request);
    }
}
