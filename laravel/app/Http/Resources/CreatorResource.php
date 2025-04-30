<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CreatorResource extends JsonResource
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
			return [
				'creatorName' => $this->creatorName,
				'givenName' => $this->givenName,
				'familyName' => $this->familyName,
			];
		}
		else
			return parent::toArray($request);
    }
}
