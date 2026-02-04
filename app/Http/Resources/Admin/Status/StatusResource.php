<?php

namespace App\Http\Resources\Admin\Status;

use Illuminate\Http\Resources\Json\JsonResource;
class StatusResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'sn' => ++$request->start,
            'id' => $this->id,
            'badge' => $this->status_badge,
            'name' => $this->name,
            'text_colour' => $this->text_colour??'N/A',
            'background_colour' => $this->background_colour??'N/A',
        ];
    }
}
