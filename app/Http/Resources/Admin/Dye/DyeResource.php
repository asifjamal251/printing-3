<?php

namespace App\Http\Resources\Admin\Dye;

use Illuminate\Http\Resources\Json\JsonResource;
class DyeResource extends JsonResource
{



    public function toArray($request)
    {
        return [
            'sn' => ++$request->start,
            'id' => $this->id,
            'dye_number' => $this->dye_number,
            'type' => $this->type,
            'sheet_size' => $this->sheet_size,
            'dye_type' => $this->dye_type,
            'dye_details' => dyeDetails($this->id),
            'status_id' => $this->status_id,
            'status' => status($this->status_id),
        ];
    }
}
