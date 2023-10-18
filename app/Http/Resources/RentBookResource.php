<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RentBookResource extends JsonResource
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
			'user_id' => $this->user_id,
			'book_id' => $this->book_id,
			'star_time' => $this->star_time,
			'end_time' => $this->end_time,
			'is_taken' => $this->is_taken,
			'created_at' => $this->created_at,
			'updated_at' => $this->updated_at
        ];
    }
}
