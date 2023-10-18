<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
			'surname' => $this->surname,
			'email' => $this->email,
			'password' => $this->password,
			'image' => $this->image,
			'qr_code' => $this->qr_code,
			'group' => $this->group,
			'role' => $this->role,
			'created_at' => $this->created_at,
			'updated_at' => $this->updated_at
        ];
    }
}
