<?php

namespace App\Http\Resources\Admin\JobCard;

use Illuminate\Http\Resources\Json\JsonResource;

class JobCardResource extends JsonResource
{

    protected function selectedJobCard($id)
    {
        if (session('job_card')) {
            foreach (session('job_card') as $details) {
                if ($details['job_card_id'] === $id) {
                    return 1;
                }
            }
        }
        return 0;
    }

    
    public function toArray($request){
        $firstAttachment = $this->firstAttachment();

        $checkboxHtml = '';
        if (in_array($this->status_id, [3])) {
            $checkboxHtml = '';
        } else {
            $checkedAttr = $this->selectedJobCard($this->id) ? 'checked' : '';
            $checkboxHtml = '
                <div class="form-check form-check-success mb-0">
                    <input class="form-check-input selectJobCard" type="checkbox" value="' . $this->id . '" id="checkbox_' . $this->id . '" ' . $checkedAttr . '>
                </div>';
        }

        return [
            'sn' => ++$request->start,
            'id' => $this->id,
            'checkbox' => $checkboxHtml,
            'printing_operator' => $this->stages
                    ->firstWhere('name', 'Printing')
                    ?->operator
                    ?->name ?? 'NA',
            'job_type' => $this->job_type,
            'job' => $this->job_type . '-' . $this->set_number,
            'job_card_number' => $this->job_card_number,
            'sheet_size' => $this->sheet_size ?? '--',
            'required_sheet' => $this->required_sheet ?? '--',
            'wastage_sheet' => $this->wastage_sheet ?? '--',
            'total_sheet' => $this->total_sheet ?? '--',
            'set_number' => $this->set_number ?? '--',
            'tentative_date' => $this->tentative_date?->format('d F Y'),
            'created_at' => '<div class="col"><p class="mt-0 mb-0">'. $this->created_at?->format('d F Y').'</p><p class="text-success mt-0 mb-0">'.$this->completed_at?->format('d F Y').'</p></div>',
            'completed_at' => $this->completed_at?->format('d F Y') ?? '--',
            'status' => status($this->status_id),
            'file' => $firstAttachment ? getFile($firstAttachment->file, $this->id) : 'N/A',
            'status_id' => $this->status_id,
            'items' => tableJobCardItems($this->id),
            'selected_job_card' => $this->selectedJobCard($this->id),
        ];
    }
}