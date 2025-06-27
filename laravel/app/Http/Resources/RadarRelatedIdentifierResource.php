<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

use App\Models\Metadataschema;
use App\Models\RelatedIdentifier;

class RadarRelatedIdentifierResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(Request $request): array
	{
		$relatedidentifiertype = Metadataschema::value($this->relatedidentifiertype);
		$relationtype = RelatedIdentifier::valueRelation($this->relationtype);

		$array = [
			'value' => RelatedIdentifier::internalUrl($this->name, $this->name),
			'relatedIdentifierType' => $relatedidentifiertype,
			'relationType' => $relationtype,
		];

		return $array;
	}
}
