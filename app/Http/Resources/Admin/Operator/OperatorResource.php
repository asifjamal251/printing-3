<?php

namespace App\Http\Resources\Admin\Operator;

use Illuminate\Http\Resources\Json\JsonResource;
class OperatorResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'sn' => ++$request->start,
            'id' => $this->id,
            'name' => $this->name,
            'module' => $this->module?->name,
            'login' => $this->admin?->email,
            'city' => $this->city?$this->city->name:'N/A',
            'status' => status($this->status_id),
            'status_id' => $this->status_id
        ];
    }
}
