<?php

namespace App\Http\Resources\Admin\OrderSheet;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderSheetResource extends JsonResource
{
    protected function addedProcessing($id)
    {
        if (session('order_sheet')) {
            foreach (session('order_sheet') as $details) {
                if ($details['order_sheet_id'] === $id) {
                    return 1;
                }
            }
        }
        return 0;
    }

    function yesNoBadge($value){
        if (is_null($value)) {
            return '--';
        }

        return strtolower($value) === 'yes'
            ? '<span class="badge bg-success">Yes</span>'
            : 'No';
    }


    function jobType($value){
        if (is_null($value)) {
            return '--';
        }

        return strtolower($value) === 'seperate'
            ? '<span class="badge bg-success">Seperate</span>'
            : 'Mix';
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

        if ($this->status_id != 3) {
            $upsValue = '<input type="text" class="form-control form-control-sm" name="ups[' . $this->id . ']" value="' . e($this->ups ?? '') . '" style="width:60px;" placeholder="UPS">';
        } else {
            $upsValue = $this->ups ?? '';
        }

        $currentGSM = $this->itemProcess?->gsm;
        $currentUps = $this->ups ? $this->ups : null;
        $finalQuantity = $this->final_quantity ? $this->final_quantity : $this->purchaseOrderItem?->quantity;
        $currentJobType = $this->job_type ? $this->job_type : $this->item?->lastItem?->job_type;

        $boardSize = $this->item->lastItem?->board_size ?? '--';
        $productType = $this->item->lastItem?->productType?->name ?? $this->itemProcess?->productType?->name ?? '--';
        $gsm = $this->item->lastItem?->gsm ?? $this->itemProcess?->gsm ?? '--';

        $quantity_status = $this->quantity_status == 1 ? 'bg-success text-white border-success' : '';
        $gsm_status = $this->gsm_status == 1 ? 'bg-success text-white border-success' : '';
        $ups_status = $this->ups_status == 1 ? 'bg-success text-white border-success' : '';
        $job_type_status = $this->job_type_status == 1 ? 'bg-success text-white border-success' : '';

        return [
            'sn' => ++$request->start,
            'id' => $this->id,
            'mfg_mkdt_by' => '<div class="col"><p class="mt-0 mb-0">'.$this->item?->mfgBy?->company_name.'</p><p class="text-muted mt-0 mb-0">'.$this->item?->mkdtBy?->company_name.'</p></div>',
            'mkdt_by' => $this->item?->mkdtBy?->company_name,
            'mfg_by' => $this->item?->mfgBy?->company_name,
            'date' => '<div class="col"><p class="mt-0 mb-0">'. $this->purchaseOrder->po_date?->format('d/m/Y') .'</p><p class="text-danger mt-0 mb-0">' . $this->item?->lastItem?->created_at?->format('d/m/Y') . '</p></div>',
            'last_date' => $this->item?->lastItem?->created_at?->format('d/m/Y')??'--',
            'item' => '<div class="col">' .sprintf(
                '<div class="cell-200 more-less"
                    data-bs-toggle="tooltip"
                    title="%s">%s</div>',
                e($this->purchaseOrderItem?->item_name),
                e(trim(preg_split('/\bwith\b/i', $this->purchaseOrderItem?->item_name)[0]))
            ) . ' <p class="text-success mt-0 mb-0">' . $this->purchaseOrderItem?->item_size. '</p></div>',
            'item_size' => $this->purchaseOrderItem?->item_size ?? '',
            'job_type' => $this->jobType($this->item?->lastItem?->job_type),
            'colour' => sprintf(
                '<div class="cell-85 more-less" 
                    data-bs-toggle="tooltip"
                    title="%s">%s</div>',
                e($this->item?->colour),
                e($this->item?->colour)
            ),
            'old_new' => $this->item?->lastItem ? 'Old' : 'New',
            'paper_type' => trim("{$productType}-{$gsm}", '-'),
            'paper_size' => $this->item->lastItem?->board_size ?? '--',
            'gsm' => $this->item->lastItem?->gsm ?? $this->itemProcess?->gsm,
            'ups' => $this->item?->lastItem?->ups ??'--',
            'number_of_sheet' => $this->item?->lastItem?->number_of_sheet ??'--',
            'last_quantity' => ( $this->item?->lastItem?->quantity ?? '--' ) . '<br>' . $this->jobType($this->item?->lastItem?->job_type),
            'dye_number' => $this->item?->lastItem?->dye->dye_number ?? '--',
            'set_number' => $this->item?->lastItem?->set_number ?? '--',
            'coating' => '<div class="col"><p class="mt-0 mb-0">'.$this->item?->coatingType->name.'</p><p class="text-muted mt-0 mb-0">'.$this->item?->otherCoatingType->name.'</p></div>',
            'embossing' => $this->yesNoBadge($this->item?->embossing),
            'leafing' => $this->yesNoBadge($this->item?->leafing),
            'braille' => $this->yesNoBadge($this->item?->braille),
            'back_print' => $this->yesNoBadge($this->item?->back_print),
            'po_quantity' => $this->purchaseOrderItem?->quantity ?? '--',
            'final_quantity' => $this->status_id == 3 ? $finalQuantity : '<input data-id="'.$this->id.'" placeholder="Final Quantity" type="number" class="'.$quantity_status.' form-control form-control-sm finalQuantity" name="final_quantity" value="'. $finalQuantity .'">'
            ,
            'current_job_type' => $this->status_id == 3 ? $this->job_type : '<div class="sm-form-control"><select data-id="'.$this->id.'" name="current_job_type" value="'.$this->job_type.'" class="currentJobType form-control form-control-sm '.$job_type_status.' ">
                    <option value="">Select Job Type</option>
                    <option value="Seperate" '.($this->job_type == "Seperate" ? "selected" : "").'>Seperate</option>
                    <option value="Mix" '.($this->job_type == "Mix" ? "selected" : "").'>Mix</option>
                 </select></div>',

            'urgent' => $this->status_id == 3 ? $this->urgent : '<div class="sm-form-control"><select data-id="'.$this->id.'" name="urgent" value="'.$this->urgent.'" class="urgent form-control form-control-sm">
                    <option value="No" '.($this->urgent == "No" ? "selected" : "").'>No</option>
                    <option value="Yes" '.($this->urgent == "Yes" ? "selected" : "").'>Yes</option>
                 </select></div>',

            'current_ups' => $this->status_id == 3 ? $currentUps : '<input data-id="'.$this->id.'" placeholder="UPS" type="number" class="'.$ups_status.' form-control form-control-sm currentUps" name="current_ups" value="'. $currentUps .'" style="width:50px;">',


            'current_gsm' => $this->status_id == 3 ? $currentGSM : '<input data-id="'.$this->id.'" placeholder="GSM" type="number" class="'.$gsm_status.' form-control form-control-sm currentGSM" name="gsm" value="'. $currentGSM .'" style="width:60px;">',

            'po_date' => $this->purchaseOrder->po_date?->format('d/m/Y') ?? '',


            'po_remarks' => $this->purchaseOrder?->remarks ?? '',
            'po_item_remarks' => $this->purchaseOrderItem?->remarks ?? '',
            'rate' => $this->purchaseOrderItem?->rate ?? 0,

            'checkbox' => $checkboxHtml,
            'added_processing' => $this->addedProcessing($this->id),
            'status' => status($this->status_id),
            'status_id' => $this->status_id,
        ];
    }
}