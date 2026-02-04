<?php

namespace App\Http\Resources\Admin\CartonRate;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Role;
use App\Models\Admin;

class CartonRateResource extends JsonResource{


    public function toArray($request){

        $productType = $this->itemProcessDetails?->productType?->name ?? $this->itemProcess?->productType?->name ?? null;
        $gsm = $this->item?->lastItem?->gsm ?? $this->itemProcess?->gsm ?? null;
        $paper = $this->jobCard?->jobCardProducts?->pluck('product.full_name')->filter()->unique()->implode(', ');
        

        $jobCardProduct = $this->jobCard?->jobCardProducts?->first();

        $paper = !empty($paper) ? $paper : ( trim( implode('-', array_filter([$productType, $gsm])), '-' ) ?: '--' );

        $paperDivideText = $jobCardProduct?->paper_divide ? '1/' . $jobCardProduct->paper_divide : '--';

        $checkboxHtml = '';
        if (in_array($this->status_id, [3,6])) {
        } else {
            $checkboxHtml = '
                <div class="form-check form-check-success mb-0">
                    <input class="form-check-input cartonRate" type="checkbox" value="' . $this->id . '" id="checkbox_' . $this->id . '">
                </div>';
        }

        return [
            'sn' => ++$request->start,
            'id' => $this->id,
            'checkbox' => $checkboxHtml,
            'mfg_mkdt_by' => '<div class="col"><p class="mt-0 mb-0">'.$this?->item?->mfgBy?->company_name.'</p><p class="text-muted mt-0 mb-0">'.$this?->item?->mkdtBy?->company_name.'</p></div>',
            'mkdt_by' => $this->item?->mkdtBy?->company_name,
            'mfg_by' => $this->item?->mfgBy?->company_name,
            'item' => '<div class="col">' .sprintf(
                '<div class="cell-200 more-less"
                    data-bs-toggle="tooltip"
                    title="%s">%s</div>',
                e($this->item_name),
                e(trim(preg_split('/\bwith\b/i', $this->item_name)[0]))
            ) . ' <p class="text-success mt-0 mb-0">' . $this->item_size. '</p></div>',
            'item_name' => $this->purchaseOrderItem?->item_name ?? '',
            'item_size' => $this->purchaseOrderItem?->item_size ?? '',
            'job_type' => $this->itemProcessDetail?->job_type ?? '--',
            'colour' => $this->itemProcessDetail?->colour ?? '--',
            'paper_size' => $this->itemProcessDetail?->jobCard?->jobCardProducts?->pluck('product.full_name')->filter()->unique()->implode(', ') ?? '--',
            'gsm' => $this->itemProcessDetail?->gsm ?? '--',
            'ups' => $this->ups,
            'number_of_sheet' => $this->itemProcessDetail?->number_of_sheet ?? '--',
            'quantity' => $this->itemProcessDetail?->quantity ?? '--',
            'paper_type' => $this->itemProcessDetail?->productType?->name ?? '--',
            'paper' => $paper . ' - ('. $paperDivideText .')',
            'paper_devide' => $paperDivideText,
            'dye_number' => $this->itemProcessDetail?->dye?->dye_number ?? '--',
            'set_number' => $this->jobCard?->set_number ?? '--',
            'coating' => '<div class="col"><p class="mt-0 mb-0">'.$this->item?->coatingType->name.'</p><p class="text-muted mt-0 mb-0">'.$this->item?->otherCoatingType->name.'</p></div>',
            'other_coating' => $this->itemProcessDetail?->other_coating ?? '--',
            'embossing' => $this->itemProcessDetail?->embossing ?? '--',
            'leafing' => $this->itemProcessDetail?->leafing ?? '--',
            'back_print' => $this->itemProcessDetail?->back_print ?? '--',
            'braille' => $this->itemProcessDetail?->braille ?? '--',
            'urgent' => $this->itemProcessDetail?->urgent ?? '--',
            'rate' => in_array($this->status_id, [3, 5])
                        ? $this->rate
                        : '<input data-id="' . $this->id . '" placeholder="Rate" type="text"
                            class="form-control form-control-sm rate"
                            name="rate" value="' . $this->rate . '" style="max-width:100px;">',
            'created_at' => $this->created_at?->format('d/m/Y'),
            'status_id' => $this->status_id,
            'status' => status($this->status_id),
        ];
    }
}