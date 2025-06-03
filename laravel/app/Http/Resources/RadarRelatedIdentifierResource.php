<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

use App\Models\Database;

class RadarRelatedIdentifierResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(Request $request): array
	{
		$relatedidentifiertype = Database::relatedidentifierValue($this->relatedidentifiertype);
		$relationtype = Database::relationValue($this->relationtype);

		$array = [
			'value' => $this->name,
			'relatedIdentifierType' => $relatedidentifiertype,
			'relationType' => $relationtype,
		];
		return $array;
	}
}
