<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Store\StoreCollection;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StoreController extends Controller
{
    
    public function create(Request $request )
    {
        return view('admin.store.create');
    }

    
    public function store(Request $request) {

         $request->validate([
            'name' => [
                'required',
                Rule::unique('stores', 'name'),
            ],
        ]);

        $store = new Store;
        $store->name = $request->name;
        if($store->save()){ 
            return response()->json(['class' => 'bg-success', 'error' => false, 'message' => 'Store Saved Successfully', 'call_back' => '', 'table_referesh' => true, 'model_id' => 'dataSave']);
        }
        return response()->json(['class' => 'bg-danger', 'error' => true, 'message' => 'Something went wrong', 'call_back' => '', 'table_referesh' => true, 'model_id' => 'dataSave']);
    }
        
    
    public function edit(Request $request, $id)
    {
        $store = Store::find($id);
        return view('admin.store.edit',compact('store'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:255|unique:stores,name,' . $id,
        ]);

        $store = Store::findOrFail($id);
        $store->name = $request->name;

        if ($store->save()) {
            return response()->json([
                'class' => 'bg-success',
                'error' => false,
                'message' => 'Store Updated Successfully',
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
}
