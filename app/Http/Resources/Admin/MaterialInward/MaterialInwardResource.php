<?php

namespace App\Http\Resources\Admin\MaterialInward;

use Illuminate\Http\Resources\Json\JsonResource;
class MaterialInwardResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'sn' => ++$request->start,
            'id' => $this->id,
            'receipt_no' => $this->receipt_no,
            'date' => $this->bill_date->format('d F, Y'),
            'vendor' => $this->vendor->company_name,
            'items' => $this->items->count(),
            'total' => $this->total,
            'receipt_by' => $this->receiptBy->name,
            'status_id' => $this->status_id,
            'status' => status($this->status_id),
        ];
    }
}
