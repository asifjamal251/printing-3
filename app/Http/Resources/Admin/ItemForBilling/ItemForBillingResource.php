<?php

namespace App\Http\Resources\Admin\ItemForBilling;

use App\Models\Module;
use App\Models\Operator;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemForBillingResource extends JsonResource
{
    protected function forBillingItem($id)
    {
        if (session('item_for_billing')) {
            foreach (session('item_for_billing') as $details) {
                if ($details['item_for_billing_id'] === $id) {
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
            $checkedAttr = $this->forBillingItem($this->id) ? 'checked' : '';
            $checkboxHtml = '
                <div class="form-check form-check-success mb-0">
                    <input class="form-check-input selectForBilling" type="checkbox" value="' . $this->id . '" id="checkbox_' . $this->id . '" ' . $checkedAttr . '>
                    <label class="form-check-label" for="checkbox_' . $this->id . '"></label>
                </div>';
        }


        return [
            'sn'   => ++$request->start,
            'id'   => $this->id,

            'mfg_mkdt_by' => '
                <div class="col">
                    <p class="mt-0 mb-0">'.$this->item?->mfgBy?->company_name.'</p>
                    <p class="text-muted mt-0 mb-0">'.$this->item?->mkdtBy?->company_name.'</p>
                </div>
            ',

             'item' => '<div class="col">' .sprintf(
                '<div class="cell-200 more-less"
                    data-bs-toggle="tooltip"
                    title="%s">%s</div>',
                e($this->item_name),
                e(trim(preg_split('/\bwith\b/i', $this->item_name)[0]))
            ) . ' <p class="text-success mt-0 mb-0">' . $this->item_size. '</p></div>',

             'quantity_per_box' => $this->quantity_per_box,
             'number_of_box' => $this->number_of_box,
             'total_quantity' => $this->total_quantity,
             'po_number' => $this->purchaseOrder->po_number,
             'rate' => $this->jobCardItem->rate,
             'rate_status' => status($this->jobCardItem->status_id),

            'checkbox' => $checkboxHtml,
            'added_for_billing' => $this->forBillingItem($this->id),


            'status_id' => $this->status_id,
            'status'    => status($this->status_id),
        ];
    }
}