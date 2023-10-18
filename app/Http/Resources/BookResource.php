<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
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
			'name' => $this->name,
			'status' => $this->status,
			'description' => $this->description,
			'created_at' => date('Y-m-d H:i:s', strtotime($this->created_at)),
			'updated_at' => date('Y-m-d H:i:s', strtotime($this->updated_at))
        ];
    }
}
