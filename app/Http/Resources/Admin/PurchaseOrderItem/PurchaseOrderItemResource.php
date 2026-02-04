<?php

namespace App\Http\Resources\Admin\PurchaseOrderItem;

use App\Models\PurchaseOrderItemItem;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;
class PurchaseOrderItemResource extends JsonResource
{


    public function toArray($request)
    {
        $productType = $this->productType?->name;
        $gsm = $this->gsm;

        return [
            'sn' => ++$request->start,
            'id' => $this->id,
            'po_from' => $this->purchaseOrder?->client?->company_name,
            'mfg_mkdt' => '<div class="col"><p class="mt-0 mb-0">'.$this->item?->mfgBy?->company_name.'</p><p class="text-muted mt-0 mb-0">'.$this->item?->mkdtBy?->company_name.'</p></div>',

            'po_date' => $this->purchaseOrder->po_date?->format('d/m/Y'),

            'item' => '<div class="col">' .sprintf(
                '<div class="cell-200 more-less"
                    data-bs-toggle="tooltip"
                    title="%s">%s</div>',
                e($this?->item_name),
                e(trim(preg_split('/\bwith\b/i', $this?->item_name)[0]))
            ) . ' <p class="text-success mt-0 mb-0">' . $this?->item_size. '</p></div>',

            'color' => sprintf(
                '<div class="cell-85 more-less" 
                    data-bs-toggle="tooltip"
                    title="%s">%s</div>',
                e($this->item?->colour),
                e($this->item?->colour)
            ),

            'paper' => trim("{$productType}-{$gsm}", '-'),
            'quantity' => $this->quantity,

            'coating' => '<div class="col"><p class="mt-0 mb-0">'.$this->coatingType?->name.'</p><p class="text-muted mt-0 mb-0">'.$this->otherCoatingType?->name.'</p></div>',

            'embossing' => $this->embossing,
            'leafing' => $this->leafing,
            'braille' => $this->braille,
            'back_print' => $this->back_print,
            'remarks' => $this->remarks,

            'created_at' => $this->created_at->format('d/m/Y'),
            'status' => status($this->status_id),
            'status_id' => $this->status_id,
        ];
    }
}
