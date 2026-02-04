<?php

namespace App\Http\Resources\Admin\Pasting;

use App\Models\Module;
use App\Models\Operator;
use Illuminate\Http\Resources\Json\JsonResource;

class PastingResource extends JsonResource
{
    public function toArray($request)
    {
        $admin = auth('admin')->user();

        $moduleIds = Module::where('model_name', $this->stage->name)->pluck('id');

        $operators = Operator::where('status_id', 14)
            ->whereIn('module_id', $moduleIds);

        if ($admin->listing_type === 'Own') {
            $operators->where('admin_id', $admin->id);
        }

        $operators = $operators->get();
        $currentOperatorId = $this->operator_id;

        /*
        |--------------------------------------------------------------------------
        | Quantity & Percentage Logic
        |--------------------------------------------------------------------------
        */

        $poQty = (int) ($this->purchaseOrderItem->quantity ?? 0);
        $pastedQty = (int) ($this->items?->sum('total_quantity') ?? 0);

        // Allow up to +2%
        $maxAllowed = $poQty + ($poQty * 2 / 100);

        if ($pastedQty > $maxAllowed) {
            $qtyClass = 'text-danger';
        } elseif ($pastedQty < $poQty) {
            $qtyClass = 'text-warning';
        } else {
            $qtyClass = 'text-success';
        }

        // Percentage difference (optional tooltip)
        $diffPercent = $poQty > 0
            ? round((($pastedQty - $poQty) / $poQty) * 100, 2)
            : 0;

        return [
            'sn'   => ++$request->start,
            'id'   => $this->id,
            'job'  => $this->jobCard->set_number,
            'file' => '--',

            'mfg_mkdt_by' => '
                <div class="col">
                    <p class="mt-0 mb-0">'.$this->item?->mfgBy?->company_name.'</p>
                    <p class="text-muted mt-0 mb-0">'.$this->item?->mkdtBy?->company_name.'</p>
                </div>
            ',

            'item' => $this->item?->item_name,

            'po_quantity' => $poQty,

            'pasted_quantity' => sprintf(
                '<span class="%s" title="%s%%">%s</span>',
                $qtyClass,
                $diffPercent,
                $pastedQty
            ),

            'die_details' => $this->jobCard->dye?->dye_info ?? 'New',

            'operator' => $this->status_id == 3
                ? e($this->operator?->name)
                : '
                    <div class="sm-form-control">
                        <select 
                            data-id="'.$this->id.'" 
                            name="operator_id" 
                            class="operator form-control form-control-sm js-choice"
                        >
                            <option value="">Select Operator</option>'
                            . $operators->map(function ($operator) use ($currentOperatorId) {
                                return '<option value="'.$operator->id.'" '
                                    . ($operator->id == $currentOperatorId ? 'selected' : '')
                                    . '>'.$operator->name.'</option>';
                            })->implode('')
                        . '</select>
                    </div>
                ',

            'status_id' => $this->status_id,
            'status'    => status($this->status_id),
            'date' => 
                    '<p class="mb-1">' . ($this->created_at?->format('d/m/y') ?? '-') . '</p>' .
                    '<p class="m-0 text-success">' . ($this->completed_at?->format('d/m/y') ?? '-') . '</p>'
        ];
    }
}