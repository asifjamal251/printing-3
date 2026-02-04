<?php

namespace App\Http\Resources\Admin\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{


    public function totalWeight($stocks){

        $total = 0;
        foreach($stocks as $stock){
            $total += ($stock->quantity/$stock->productAttribute->item_per_packet) * $stock->productAttribute->weight_per_piece;
        }
        return number_format($total, 2, '.', '') . ' KG';
    }


    public function toArray(Request $request): array
    {
        return [
            'sn' => ++$request->start,
            'id'=>$this->id,
            'name' => $this->product_name_gsm,
            'product_type' => $this->productType?->name,
            'quantity' => $this->stocks->sum('quantity') . ' ' . ($this->unit->name ?? ''),
            'total_weight' => $this->totalWeight($this->stocks),
        ];
    }
}
