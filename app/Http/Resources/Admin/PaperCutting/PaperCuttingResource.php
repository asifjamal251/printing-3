<?php

namespace App\Http\Resources\Admin\PaperCutting;

use App\Models\Module;
use App\Models\Operator;
use Illuminate\Http\Resources\Json\JsonResource;

class PaperCuttingResource extends JsonResource
{
    public function toArray($request)
    {
        $admin = auth('admin')->user();
        $moduleIds = Module::where('model_name', $this->stage->name)->pluck('id');
        $operators = Operator::where('status_id', 14)->whereIn('module_id', $moduleIds);
        if ($admin->listing_type === 'Own') {
            $operators->where('admin_id', $admin->id);
        }
        $operators = $operators->get();
        $currentOperatorId = $this->operator_id;

        return [
            'sn'        => ++$request->start,
            'id'        => $this->id,
            'job'       => $this->jobCard->set_number,
            'sheet_size'=> $this->jobCard->sheet_size,
            'items' => tableJobCardItems($this->job_card_id),

            'product' => $this->jobCard?->jobCardProducts
                        ->map(function ($paper) {
                            return
                                ($paper?->product?->product_name ?? '-') .
                                ' - ' .
                                ($paper?->product?->productType?->name ?? '-') .
                                ' - ' .
                                ($paper?->product?->gsm ?? '-');
                        })
                        ->implode('<hr class="border-secondary my-1">'),

            'total_sheet' => $this->jobCard?->jobCardProducts
                        ->map(function ($paper) {
                            return ($paper?->total_sheet ?? '-');
                        })
                        ->implode('<hr class="border-secondary my-1">'),
            'required_sheet_wastage' => $this->jobCard?->jobCardProducts
                            ->map(function ($paper) {
                                $required = $paper?->required_sheet ?? 0;
                                $wastage  = $paper?->wastage_sheet ?? 0;

                                return ($required + $wastage) ?: '-';
                            })
                            ->implode('<hr class="border-secondary my-1">'),
                            
            'impression' => $this->jobCard?->jobCardProducts
                            ->map(function ($paper) {
                                $required = $paper?->required_sheet ?? 0;
                                $wastage  = $paper?->wastage_sheet ?? 0;

                                return ($required + $wastage) ?: '-';
                            })
                            ->implode('<hr class="border-secondary my-1">'),

            'required_sheet' => $this->jobCard?->jobCardProducts
                        ->map(function ($paper) {
                            return ($paper?->required_sheet ?? '-');
                        })
                        ->implode('<hr class="border-secondary my-1">'),

            'wastage' => $this->jobCard?->jobCardProducts
                        ->map(function ($paper) {
                            return ($paper?->wastage_sheet ?? '-');
                        })
                        ->implode('<hr class="border-secondary my-1">'),

            'paper_divide' => $this->jobCard?->jobCardProducts
                        ->map(function ($paper) {
                            return ($paper?->paper_divide ?? '-');
                        })
                        ->implode('<hr class="border-secondary my-1">'),

            'operator'  => $this->status_id == 3
                ? e($this->operator?->name)
                : '
                    <div class="sm-form-control">
                        <select 
                            data-id="'.$this->id.'" 
                            name="operator_id" 
                            class="operator form-control form-control-sm"
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

            'counter' => $this->status_id == 3 ? $this->counter : '<input data-id="'.$this->id.'" placeholder="Counter" type="number" class="form-control form-control-sm counter" name="counter" value="" style="width:100px;">',

            'status_id' => $this->status_id,
            'status'    => status($this->status_id),
            'date' => 
                    '<p class="mb-1">' . ($this->created_at?->format('d/m/y') ?? '-') . '</p>' .
                    '<p class="m-0 text-success">' . ($this->completed_at?->format('d/m/y') ?? '-') . '</p>'
        ];
    }
}