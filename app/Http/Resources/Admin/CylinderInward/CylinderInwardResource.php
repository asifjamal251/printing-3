<?php

namespace App\Http\Resources\Admin\CylinderInward;

use Illuminate\Http\Resources\Json\JsonResource;
class CylinderInwardResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'sn' => ++$request->start,
            'id' => $this->id,
            'client' => $this->client->company_name,
            'vendor' => $this->vendor->company_name,
            'bill_no' => $this->bill_no,
            'bill_date' => $this->bill_date?->format('d F Y'),
        ];
    }
}
