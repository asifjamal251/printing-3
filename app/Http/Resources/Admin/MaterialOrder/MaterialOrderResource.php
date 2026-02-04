<?php

namespace App\Http\Resources\Admin\MaterialOrder;

use Illuminate\Http\Resources\Json\JsonResource;
class MaterialOrderResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'sn' => ++$request->start,
            'id' => $this->id,
            'order_no' => $this->order_no,
            'date' => $this->mo_date->format('d F, Y'),
            'bill_ship_to' => '<p class="mb-0">'.$this->billTo->company_name.'</p><p class="m-0 text-success">'.$this->billTo->company_name.'</p>',
            'vendor' => $this->vendor->company_name,
            'items' => $this->items->count(),
            'total' => $this->total,
            'order_by' => $this->orderBy->name,
            'status_id' => $this->status_id,
            'status' => status($this->status_id),
        ];
    }
}
