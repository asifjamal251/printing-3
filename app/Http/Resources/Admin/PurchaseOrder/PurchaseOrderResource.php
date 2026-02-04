<?php

namespace App\Http\Resources\Admin\PurchaseOrder;

use App\Models\PurchaseOrderItem;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;
class PurchaseOrderResource extends JsonResource
{

    public function itemStatus($id){
        $itemsCountByState = PurchaseOrderItem::where('purchase_order_id', $id)
                            ->select('status_id', DB::raw('count(*) as count'))
                            ->groupBy('status_id')
                            ->get();

        $statusCounts = [];

        foreach ($itemsCountByState as $itemCount) {
            $statusCounts[$itemCount->status_id] = '<span>'.$itemCount->count. '-' .status($itemCount->status_id).'</span>';
        }

        return $statusCounts;
    }

    public function toArray($request)
    {
        $itemStatus = $this->itemStatus($this->id);
        return [
            'sn' => ++$request->start,
            'id' => $this->id,
            'client' => $this?->client?->company_name,
            'po_number' => $this->po_number,
            'remarks' => sprintf(
                '<div class="cell-85 more-less" 
                    data-bs-toggle="tooltip"
                    title="%s">%s</div>',
                e($this->remarks),
                e($this->remarks)
            ),
            'created_by' => $this->addedBy->name,
            'items' => tablePOItems($this->id),
            'items_status' => $itemStatus,
            'po_date' => $this->po_date->format('d/m/Y'),
            'created_at' => $this->created_at->format('d/m/Y'),
            'status' => status($this->status_id),
            'status_id' => $this->status_id,
        ];
    }
}
