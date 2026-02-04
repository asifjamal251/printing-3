<?php

namespace App\Http\Resources\Admin\Admin;

use Illuminate\Http\Resources\Json\JsonResource;
class AdminResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'sn' => ++$request->start,
            'id' => $this->id,
            'role' => $this->role->name,
            'name' => $this->name,
            'email' => $this->email,
            'status' => status($this->status_id),
        ];
    }
}
