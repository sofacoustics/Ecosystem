<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DatabaseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
		//dd('DatabaseResource');
        return parent::toArray($request);
//        return [
//            'id' => $this->id,
//			'parentId' => env("RADAR_WORKSPACE"),
//            'name' => $this->name,
//        ];
    }
}
