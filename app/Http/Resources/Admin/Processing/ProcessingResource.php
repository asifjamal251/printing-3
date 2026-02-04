<?php

namespace App\Http\Resources\Admin\Processing;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Role;
use App\Models\Admin;

class ProcessingResource extends JsonResource
{
    protected function addedProcessing($id)
    {
        if (session('processing')) {
            foreach (session('processing') as $details) {
                if ($details['processing_id'] === $id) {
                    return 1;
                }
            }
        }
        return 0;
    }

    public function toArray($request)
    {
        $checkboxHtml = '';
        if (in_array($this->status_id, [3])) {
            $checkboxHtml = $this->jobCard?->job_card_number;
        } else {
            $checkedAttr = $this->addedProcessing($this->id) ? 'checked' : '';
            $checkboxHtml = '
                <div class="form-check form-check-success mb-0">
                    <input class="form-check-input makeProcessing" type="checkbox" value="' . $this->id . '" id="checkbox_' . $this->id . '" ' . $checkedAttr . '>
                    <label class="form-check-label" for="checkbox_' . $this->id . '"></label>
                </div>';
        }

        $ups = $this->itemProcessDetail?->ups;

        // ✅ Fetch designers
        $designerRole = Role::where('name', 'designer')->first();
        $designers = $designerRole ? Admin::where('role_id', $designerRole->id)->get() : collect();

        // ✅ If designer already selected, show name; else show dropdown
        if ($this->status_id == 3) {
            $designerHtml = $this->processing->adminDesigner->name;
        } else {
            $designerHtml = '<div class="sm-form-control" style="min-width:130px;"><select id="designer' . $this->id . '" data-id="' . $this->id . '" class="form-select form-select-sm designerSelect" style="min-width:130px;">';
            $designerHtml .= '<option value="">Select Designer</option>';

            foreach ($designers as $designer) {
                $selected = $this->processing->designer == $designer->id ? 'selected' : '';
                $designerHtml .= '<option value="' . $designer->id . '" ' . $selected . '>' . e($designer->name) . '</option>';
            }

            $designerHtml .= '</select><div>';
        }

        $boardSize = $this->item->lastItem?->board_size ?? '--';
        $productType = $this->item->lastItem?->productType?->name ?? $this->itemProcess?->productType?->name ?? '--';
        $gsm = $this->item->lastItem?->gsm ?? $this->itemProcess?->gsm ?? '--';

        return [
            'sn' => ++$request->start,
            'id' => $this->id,
            'checkbox' => $checkboxHtml,
            'mfg_mkdt_by' => '<div class="col"><p class="mt-0 mb-0">'.$this->item?->mfgBy?->company_name.'</p><p class="text-muted mt-0 mb-0">'.$this->item?->mkdtBy?->company_name.'</p></div>',
            'mkdt_by' => $this->item?->mkdtBy?->company_name,
            'mfg_by' => $this->item?->mfgBy?->company_name,
            'item' => '<div class="col">' .sprintf(
                '<div class="cell-200 more-less"
                    data-bs-toggle="tooltip"
                    title="%s">%s</div>',
                e($this->purchaseOrderItem?->item_name),
                e(trim(preg_split('/\bwith\b/i', $this->purchaseOrderItem?->item_name)[0]))
            ) . ' <p class="text-success mt-0 mb-0">' . $this->purchaseOrderItem?->item_size. '</p></div>',
            'po_remarks' => $this->purchaseOrder?->remarks,
            'po_item_remarks' => $this->purchaseOrderItem?->remarks,
            'item_name' => $this->purchaseOrderItem?->item_name ?? '',
            'item_size' => $this->purchaseOrderItem?->item_size ?? '',
            'job_type' => $this->itemProcessDetail?->job_type ?? '--',
            'colour' => sprintf(
                '<div class="cell-85 more-less" 
                    data-bs-toggle="tooltip"
                    title="%s">%s</div>',
                e($this->item?->colour),
                e($this->item?->colour)
            ),
            'paper' => trim("{$productType}-{$gsm}", '-'),
            'paper_size' => $this->itemProcessDetail?->paper_size ?? '--',
            'gsm' => $this->itemProcessDetail?->gsm ?? '--',
            'ups' => $this->status_id == 3
                ? $ups
                : '<input data-id="' . $this->id . '" placeholder="UPS" type="number" class="form-control form-control-sm ups" name="ups" value="' . $ups . '" style="width:50px;">',
            'number_of_sheet' => $this->itemProcessDetail?->number_of_sheet ?? '--',
            'quantity' => $this->itemProcessDetail?->quantity ?? '--',
            'paper_type' => $this->itemProcessDetail?->productType?->name ?? '--',
            'dye_number' => $this->itemProcessDetail?->dye?->dye_number ?? '--',
            'set_number' => $this->item?->lastItem?->set_number ?? '--',
             'coating' => '<div class="col"><p class="mt-0 mb-0">'.$this->item?->coatingType?->name.'</p><p class="text-muted mt-0 mb-0">'.$this->item?->otherCoatingType?->name.'</p></div>',
            'other_coating' => $this->itemProcessDetail?->other_coating ?? '--',
            'embossing' => $this->itemProcessDetail?->embossing ?? '--',
            'leafing' => $this->itemProcessDetail?->leafing ?? '--',
            'back_print' => $this->itemProcessDetail?->back_print ?? '--',
            'braille' => $this->itemProcessDetail?->braille ?? '--',
            'urgent' => $this->itemProcessDetail?->urgent ?? '--',

            'processing_number' => '<span class="reelNumber">' . $this->processing?->processing_number . '</span>',
            'job_card_id' => $this->job_card_id,

            'designer' => $this->status_id == 3 ? $designerHtml. ' - ' . $this->set_number : $designerHtml,

            'created_at' => $this->created_at?->format('d/m/Y'),
            'status_id' => $this->status_id,
            'status' => status($this->status_id),
            'added_processing' => $this->addedProcessing($this->id),
        ];
    }
}