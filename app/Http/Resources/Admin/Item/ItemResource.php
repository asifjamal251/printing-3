<?php

namespace App\Http\Resources\Admin\Item;

use Illuminate\Http\Resources\Json\JsonResource;
class ItemResource extends JsonResource
{

    protected function addedInPO($id){
        if (session('po_item')) {
            foreach (session('po_item') as $items) {
                if ($items['po_item_id'] === $id) {
                    return 1;
                }
            }
        }
        return 0;
    }

    public function toArray($request)
    {
        return [
            'sn' => ++$request->start,
            'id' => $this->id,
            'mfg_mkdt_by' => '<div class="col"><p class="mt-0 mb-0">'.$this->mfgBy?->company_name.'</p><p class="text-muted mt-0 mb-0">'.$this->mkdtBy?->company_name.'</p></div>',
            'mkdt_by' => $this->mkdtBy?->company_name,
            'mfg_by' => $this->mfgBy?->company_name,
            'client' => $this->mfgBy?->company_name,
            // 'item_name' => sprintf(
            //     '<div class="cell-200 more-less"
            //         data-bs-toggle="tooltip"
            //         title="%s">%s</div>',
            //     e($this->item_name),
            //     e(trim(preg_split('/\bwith\b/i', $this->item_name)[0]))
            // ),

            'item_name' => '<div class="col">' .sprintf(
                '<div class="cell-200 more-less"
                    data-bs-toggle="tooltip"
                    title="%s">%s</div>',
                e($this->item_name),
                e(trim(preg_split('/\bwith\b/i', $this->item_name)[0]))
            ) . ' <p class="text-success mt-0 mb-0">' . $this->item_size. '</p></div>',

            'item_size' => $this->item_size,
            'job_type' => $this?->lastItem?->job_type?? '--',
            'colour' => sprintf(
                '<div class="cell-85 more-less" 
                    data-bs-toggle="tooltip"
                    title="%s">%s</div>',
                e($this->colour),
                e($this->colour)
            ),
            'gsm' => $this->gsm,
            'coating' => $this->coatingType?->name,
            'other_coating' => $this->otherCoatingType?->name,
            'embossing' => $this->embossing,
            'leafing' => $this->leafing,
            'back_print' => $this->back_print,
            'braille' => $this->braille,
            'product_type' => $this->productType?->name ?? '--',
            'old_new' => $this->lastItem ? 'Old' : 'New',
            'quantity' => $this->lastItem?->quantity ?? '--',
            'printing_machine' => $this->lastItem?->printingMachine?->name ?? '--',
            'sheet_size' => $this->lastItem?->sheet_size ?? '--',
            'ups' => $this->lastItem?->ups ?? '--',
            'rate' => $this->lastItem?->rate ?? '--',
            'gst' => $this->lastItem?->gst_percentage ?? '--',
            'dye_no' => $this->lastItem?->dye?->dye_number ?? '--',
            'job_card_no' => $this->jobCard?->job_card_no ?? '--',
            'set_no' => $this->lastItem?->set_number ?? '--',
            'added_po' => $this->addedInPO($this->id),
            'status_id' => $this->status_id,
            'status' => status($this->status_id),
            'last_date' => $this->lastItem?->created_at->format('d/m/y'),
        ];
    }
}
