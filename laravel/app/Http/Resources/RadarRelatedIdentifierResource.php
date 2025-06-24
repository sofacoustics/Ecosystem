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

/*
				$this->relatedidentifier->name = route('databases.show',[ 'database' => $database->id]); // we store the URL to the database
				$this->relatedidentifier->relatedidentifiertype = \App\Models\Metadataschema::where('name', 'relatedIdentifierType')->where('value', 'URL')->first()->id;
				$this->relatedidentifier->relationtype  = $this->databaserelation;

						$this->relatedidentifier->name = route('databases.show',[ 'database' => $database->id]); // we store the URL to the database

				switch($this->toolrelation)
				{
					case "-1":
						$this->relatedidentifier->relationtype  = \App\Models\Metadataschema::where('name', 'relationType')->where('value', 'COMPILES')->first()->id;
						break;
					case "-2":
						$this->relatedidentifier->relationtype  = \App\Models\Metadataschema::where('name', 'relationType')->where('value', 'IS_COMPILED_BY')->first()->id;
						break;
					case "-3":
						$this->relatedidentifier->relationtype  = \App\Models\Metadataschema::where('name', 'relationType')->where('value', 'IS_CONTINUED_BY')->first()->id;
						break;
					default:
						$this->relatedidentifier->relationtype = $this->toolrelation;
				}
				break;
	*/	
		return $array;
	}
}
