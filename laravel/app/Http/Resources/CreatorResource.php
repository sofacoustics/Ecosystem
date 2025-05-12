<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

use App\Models\Radar;

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
			// prepare creatorAffiliation
			$creatorAffiliation = ['creatorAffiliation' => null];
			if($this->creatorAffiliation != "")
			{
				$creatorAffiliation = [
					'creatorAffiliation' => [
						'value' => $this->creatorAffiliation,
					    'schemeURI' => $this->creatorAffiliationSchemeURI,
						'affiliationIdentifierScheme' => $this->affiliationIdentifierScheme,
						'affiliationIdentifier' => $this->affiliationIdentifier,
					]
				];
			}
			// prepare nameIdentifier
			$nameIdentifier = [ 'nameIdentifier' => [] ];
			if($this->nameIdentifier != "")
			{
				$nameIdentifier = [
					'nameIdentifier' => [
						'value' => $this->nameIdentifier,
						'schemeURI' => Radar::schemeURI($this->nameIdentifierSchemeIndex),
						'nameIdentifierScheme' => Radar::nameIdentifierScheme($this->nameIdentifierSchemeIndex),
					]
				];
			}

			return [
				'creatorName' => $this->creatorName,
				'givenName' => $this->givenName,
				'familyName' => $this->familyName,
				...$nameIdentifier,
				...$creatorAffiliation,
			];
		}
		else
			return parent::toArray($request);
    }
}
