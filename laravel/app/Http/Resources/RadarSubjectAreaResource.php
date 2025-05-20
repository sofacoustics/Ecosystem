<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

use App\Models\Database;

class RadarSubjectAreaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
		$controlledSubjectArea = Database::subjectareaValue($this->controlledSubjectAreaIndex);

		return [
			'controlledSubjectAreaName' => $controlledSubjectArea,
			'additionalSubjectAreaname' => $this->additionalSubjectArea,
		];
	}
}
