<?php

namespace App\Http\Resources\Admin\ReelInward;

use Illuminate\Http\Resources\Json\JsonResource;

class ReelInwardResource extends JsonResource
{
    public function toArray($request)
    {
        

        return [
            'sn'        => ++$request->start,
            'id'        => $this->id,
            'vendor'    => $this->vendor?->company_name,
            'bill_no'   => $this->bill_no,
            'bill_date' => $this->bill_date?->format('d/m/Y'),
            'remarks'   => $this->remarks??'N/A',
            'status_id' => $this->status_id,
            'status'    => status($this->status_id),
        ];
    }
}