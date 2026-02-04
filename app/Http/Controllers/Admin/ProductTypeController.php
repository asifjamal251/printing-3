<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\ProductType\ProductTypeCollection;
use App\Models\ProductType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductTypeController extends Controller
{
    
    public function create(Request $request )
    {
        return view('admin.product-type.create');
    }

   
    public function store(Request $request) {


         $request->validate([
            'product_type' => [
                'required',
                Rule::unique('product_types', 'name'),
            ],
            'type' => 'required|in:Paper,Chemical,Other',
        ]);

        $product_type = new ProductType;
        $product_type->name = $request->product_type;
        $product_type->type = $request->type;
        if($product_type->save()){ 
            return response()->json(['class' => 'bg-success', 'error' => false, 'message' => 'Product Type Saved Successfully', 'call_back' => '', 'table_referesh' => true, 'model_id' => 'dataSave']);
        }
        return response()->json(['class' => 'bg-danger', 'error' => true, 'message' => 'Something went wrong', 'call_back' => '', 'table_referesh' => true, 'model_id' => 'dataSave']);
    }
        
   
    public function edit(Request $request, $id)
    {
        $product_type = ProductType::find($id);
        return view('admin.product-type.edit',compact('product_type'));
    }

    
    public function update(Request $request, $id)
    {
        $request->validate([
            'product_type' => 'required|max:255|unique:product_types,name,' . $id,
            'type'   => 'required|in:Paper,Chemical,Other',
        ]);

        $product_type = ProductType::findOrFail($id);
        $product_type->name = $request->product_type;
        $product_type->type = $request->type;

        if ($product_type->save()) {
            return response()->json([
                'class' => 'bg-success',
                'error' => false,
                'message' => 'Product Type Updated Successfully',
                'call_back' => '',
                'table_referesh' => true,
                'model_id' => 'dataSave'
            ]);
        }

        return response()->json([
            'class' => 'bg-danger',
            'error' => true,
            'message' => 'Something went wrong',
            'call_back' => '',
            'table_referesh' => true,
            'model_id' => 'dataSave'
        ]);
    }

    public function updateOrder(Request $request){
        $order = json_decode($request->input('order'), true);
        $this->updateNodeOrder($order);
        return response()->json(['message'=>'Paper Type Upfated Successfully ...', 'class'=>'bg-success', 'error' => false]);  
    }

    private function updateNodeOrder($nodes, $parentId = null)
    {
        foreach ($nodes as $index => $node) {
            ProductType::where('id', $node['id'])->update([
                'ordering' => $index
            ]);
            if (!empty($node['children'])) {
                $this->updateNodeOrder($node['children'], $node['id']);
            }
        }
    }


    
    public function destroy(Request $request, ProductType $product_type)
    {
        ProductType::where('parent', $product_type->id)->update(['parent'=>null]);
        if($product_type->delete()){
            return response()->json(['message'=>'ProductType deleted Successfully ...', 'class'=>'success']);  
        }
        return response()->json(['message'=>'Whoops, looks like something went wrong ! Try again ...', 'class'=>'error']);
    }


    
}
