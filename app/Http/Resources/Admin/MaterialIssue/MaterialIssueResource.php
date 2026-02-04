<?php

namespace App\Http\Resources\Admin\MaterialIssue;

use Illuminate\Http\Resources\Json\JsonResource;
class MaterialIssueResource extends JsonResource
{



    public function toArray($request)
    {
        return [
            'sn' => ++$request->start,
            'id' => $this->id,
            'type' => $this->material_issue_type,
            'department' => $this->department->name,
            'issue_number' => $this->material_issue_number,
            'issue_date' => $this->material_issue_date->format('d/m/Y'),
            'issue_by' => $this->createdBy->name,
            'remarks' => $this->remarks,
            'items' => $this->items->count(),
            'status_id' => $this->status_id,
            'status' => status($this->status_id),
        ];
    }
}
