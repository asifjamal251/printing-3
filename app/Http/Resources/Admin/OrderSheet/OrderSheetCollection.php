<?php

namespace App\Http\Resources\Admin\OrderSheet;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\Admin\OrderSheet\OrderSheetResource;
class OrderSheetCollection extends ResourceCollection
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
            'data' => OrderSheetResource::collection($this->collection),
            'recordsTotal' => $request->recordsTotal,
            'recordsFiltered' => $request->recordsTotal,
            'length' => $request->lenght,
        ];
    }
}
