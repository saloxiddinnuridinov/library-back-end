<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SubjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
			'department_id' => $this->department_id,
			'name' => $this->name,
			'tag' => $this->tag,
			'position' => $this->position,
			'description' => $this->description,
			'created_at' => $this->created_at,
			'updated_at' => $this->updated_at
        ];
    }
}
