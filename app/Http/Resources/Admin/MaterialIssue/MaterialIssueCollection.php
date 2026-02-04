<?php

namespace App\Http\Resources\Admin\MaterialIssue;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\Admin\MaterialIssue\MaterialIssueResource;
class MaterialIssueCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => MaterialIssueResource::collection($this->collection),
            'recordsTotal' => $request->recordsTotal,
            'recordsFiltered' => $request->recordsTotal,
            'length' => $request->lenght,
        ];
    }
}
