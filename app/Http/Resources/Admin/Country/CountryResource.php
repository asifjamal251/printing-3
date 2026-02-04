<?php
namespace App\Http\Resources\Admin\Country;

use Illuminate\Http\Resources\Json\JsonResource;
class CountryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'sn' => ++$request->start,
            'id'=>$this->id,
            'name'=>$this->name,
            'short_name'=>$this->short_name,
            'code'=>$this->code,
            'currency'=>$this->currency_name.'(<b>'. $this->currency_symbol .'</b>)',
        ];
    }
}
