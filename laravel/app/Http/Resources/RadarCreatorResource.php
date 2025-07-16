<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

use App\Models\Radar;

class RadarCreatorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
		// prepare creatorAffiliation
		$creatorAffiliation = ['creatorAffiliation' => null];
		if($this->creatorAffiliation != "")
		{
			$creatorAffiliation = [
				'creatorAffiliation' => [
					'value' => $this->creatorAffiliation,
					'schemeURI' => $this->creatorAffiliationSchemeURI,
					'affiliationIdentifierScheme' => Radar::nameIdentifierSchemeValue($this->affiliationIdentifierScheme),
					'affiliationIdentifier' => 'https://ror.org/'.$this->affiliationIdentifier,
				]
			];
		}
		// prepare nameIdentifier
		$nameIdentifier = [ 'nameIdentifier' => [] ];
		if($this->nameIdentifier != "")
		{
			if($this->nameIdentifierSchemeIndex)
			{
				$nameIdentifier = [
					'nameIdentifier' => [
						[
							'value' => $this->nameIdentifier,
							'schemeURI' => Radar::schemeURI($this->nameIdentifierSchemeIndex),
							'nameIdentifierScheme' => Radar::nameIdentifierSchemeValue($this->nameIdentifierSchemeIndex),
						],
					]
				];
			}
			else
			{
				$nameIdentifier = [
					'nameIdentifier' => [
						[
							'value' => $this->nameIdentifier,
						],
					]
				];
			}
		}

		return [
			'creatorName' => $this->creatorName,
			'givenName' => $this->givenName,
			'familyName' => $this->familyName,
			...$nameIdentifier,
			...$creatorAffiliation,
		];
	}
}
