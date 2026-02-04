<?php

namespace App\Http\Resources\Admin\Billing;

use Illuminate\Http\Resources\Json\JsonResource;
class BillingResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'sn' => ++$request->start,
            'id' => $this->id,
            'bill_from' => $this->firm->company_name,
            'bill_to' => $this->billTo->company_name,
            'ship_to' => $this->shipTo->company_name,
            'bill_date' => $this->bill_date?->format('d/m/y'),
            'bill_no' => $this->bill_no,
            'transporter_name' => $this->transporter_name,
            'vehicle_no' => $this->vehicle_no,
            'status_id' => $this->status_id,
            'status' => status($this->status_id),
        ];
    }
}
