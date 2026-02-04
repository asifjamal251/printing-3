<?php

namespace App\Http\Resources\Admin\Firm;

use Illuminate\Http\Resources\Json\JsonResource;
class FirmResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'sn' => ++$request->start,
            'id' => $this->id,
            'company_name' => $this->company_name,
            'email' => $this->email,
            'contact_no' => $this->contact_no??'N/A',
            'gst' => $this->gst??'N/A',
            'city' => $this->city?$this->city->name:'N/A',
            'status' => status($this->status_id),
        ];
    }
}
