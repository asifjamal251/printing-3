<?php

namespace App\Http\Resources\Admin\Product;

use App\Http\Resources\Admin\Product\ProductResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;


class ProductCollection extends ResourceCollection
{

    public function toArray(Request $request): array
    {
        return [
            'data' => ProductResource::collection($this->collection),
            'recordsTotal' => $request->recordsTotal,
            'recordsFiltered' => $request->recordsTotal,
            'length' => $request->lenght,
        ];
    }
}
