<?php

namespace App\Http\Resources\Admin\DyeCutting;

use App\Models\Module;
use App\Models\Operator;
use Illuminate\Http\Resources\Json\JsonResource;

class DyeCuttingResource extends JsonResource
{
    public function toArray($request){
        $admin = auth('admin')->user();
        $moduleIds = Module::where('model_name', $this->stage->name)->pluck('id');
        $operators = Operator::where('status_id', 14)->whereIn('module_id', $moduleIds);
        if ($admin->listing_type === 'Own') {
            $operators->where('admin_id', $admin->id);
        }
        $operators = $operators->get();
        $currentOperatorId = $this->operator_id;

        $in  = $this->stage->in_counter  ?? 0;
        $out = $this->stage->out_counter ?? 0;

        return [
            'sn'        => ++$request->start,
            'id'        => $this->id,
            'job'       => $this->jobCard->set_number,
            'file'       => '--',
            'die_details' => $this->jobCard->dye?->dye_info??'New',
            
            'total_sheet' => $in,

            'sheet_size'=> $this->jobCard->sheet_size,

            'items' => tableJobCardItems($this->job_card_id),
            'operator'  => $this->status_id == 3
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

            'counter' => $this->status_id == 3 ? $this->counter . ($out > 0 ? '<br><span class="text-danger">' . ($in - $out) . '</span>' : '' ) : '<input data-id="'.$this->id.'" placeholder="Counter" type="number" class="form-control form-control-sm counter" name="counter" value="" style="width:100px;">',

            'status_id' => $this->status_id,
            'status'    => status($this->status_id),
            'date' => 
                    '<p class="mb-1">' . ($this->created_at?->format('d/m/y') ?? '-') . '</p>' .
                    '<p class="m-0 text-success">' . ($this->completed_at?->format('d/m/y') ?? '-') . '</p>'
        ];
    }
}