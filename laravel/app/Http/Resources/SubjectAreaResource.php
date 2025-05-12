<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

use App\Models\Database;

class SubjectAreaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
		if ($request->has('format') && $request->input('format') == 'radar')
        {
            $controlledSubjectArea = Database::subjectareaDisplay($this->controlledSubjectAreaIndex);

            return [
                'controlledSubjectAreaName' => $controlledSubjectArea,
                'additionalSubjectAreaname' => $this->additionalSubjectArea,
            ];
        }
        else
            return parent::toArray($request);
    }
}
