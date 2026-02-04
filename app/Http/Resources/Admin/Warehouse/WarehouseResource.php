<?php

namespace App\Http\Resources\Admin\Warehouse;

use App\Models\Module;
use App\Models\Operator;
use Illuminate\Http\Resources\Json\JsonResource;

class WarehouseResource extends JsonResource
{
    public function toArray($request)
    {
        

        $poQty = (int) ($this->purchaseOrderItem->quantity ?? 0);
        $pastedQty = (int) ($this->items?->sum('total_quantity') ?? 0);
        $billedQuantity = (int) ($this->items?->sum('billed_quantity') ?? 0);
        $pendingQuantity = (int) ($this->items?->sum('pending_quantity') ?? 0);


        return [
            'sn'   => ++$request->start,
            'id'   => $this->id,

            'mfg_mkdt_by' => '
                <div class="col">
                    <p class="mt-0 mb-0">'.$this->item?->mfgBy?->company_name.'</p>
                    <p class="text-muted mt-0 mb-0">'.$this->item?->mkdtBy?->company_name.'</p>
                </div>
            ',

            'item' => $this->item?->item_name,

            'po_quantity' => $poQty,
            'po_number' => $this->purchaseOrder?->po_number,

            'pasted_quantity' => $pastedQty,
            'billed_quantity' => $billedQuantity,
            'pending_quantity' => $pendingQuantity,

            'status_id' => $this->status_id,
            'status'    => status($this->status_id),
        ];
    }
}