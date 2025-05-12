<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

use App\Models\Radar;

class RightsholderResource extends JsonResource
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
                'value' => $this->rightsholderName,
                'nameIdentifierScheme' => Radar::nameIdentifierScheme($this->nameIdentifierSchemeIndex),
				'nameIdentifier' => $this->nameIdentifierSchemeIndex == 0 ? null : $this->nameIdentifier,
                'schemeURI' => $this->nameIdentifierSchemeIndex == 0 ? null : Radar::schemeURI($this->nameIdentifierSchemeIndex),
            ];
		}
		else
		{
			return parent::toArray($request);
		}
    }
}
