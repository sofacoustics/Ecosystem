<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

use App\Models\Database;

/*
 * We're creating a ResourceCollection for the RadarRelatedIdentifierResource
 * because we want to prepend it with a back link to the ecosystem, and the
 * 'prepend()' function does not exist for AnonymousResourceCollections.
 * This appears to be an elegant solution.
 */
class RadarRelatedIdentifierResourceCollection extends ResourceCollection
{
	protected $parent;
	protected $database_id;
	protected $tool_id;

	public function __construct($resource, $parent)
	{
		parent::__construct($resource);
		$this->parent = $parent;
		$class = get_class($parent);
		if ("$class" == "App\Models\Database") {
			$this->database_id = $parent->id;
		}
		else if($class == "App\Models\Tool") {
			$this->tool_id = $parent->id;
		}
	}

    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
	{
		// create a fake related identifier for the information how to get back to the Ecosystem
		$infotext = [
			'value' => "Click the link above to go to the Ecosystem",
			'relatedIdentifierType' => "ARK",
			'relationType' => "CONTINUES",
		];
        $this->collection->prepend($infotext);
		// create a related identifier for the callback to Ecosystem from RADAR
		$callback = [
			'relatedIdentifierType' => "URL",
			'relationType' => "IS_DESCRIBED_BY",
		];
		if($this->database_id)
			$callback['value'] = route('databases.show',[ 'database' => $this->database_id]);
		if($this->tool_id)
			$callback['value'] = route('tools.show',[ 'tool' => $this->tool_id]);
		$this->collection->prepend($callback);
		return $this->collection->toArray();
    }
}
