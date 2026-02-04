<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DyeLockType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DyeLockTypeController extends Controller
{
    public function create(Request $request ){
        return view('admin.dye-lock-type.create');
    }



    public function store(Request $request){
        $validated = $request->validate([
            'type' => 'required|string|max:191|unique:dye_lock_types,type',
        ]);

        // Create the Source
        $lock_type = DyeLockType::create([
            'type' => $validated['type'],
        ]);


        return response()->json([
            'class' => 'bg-success',
            'error' => false,
            'message' => 'Payment Type Saved Successfully',
            'call_back' => '',
            'table_referesh' => true,
            'model_id' => 'dataSave'
        ]);
    }




    public function edit($id){
        $lock_type = DyeLockType::findOrFail($id);
        return view('admin.dye-lock-type.edit', compact('lock_type'));
    }




    public function update(Request $request, $id){
        $lock_type = DyeLockType::findOrFail($id);

        $validated = $request->validate([
            'type' => [
                'required',
                'string',
                'max:191',
                Rule::unique('dye_lock_types', 'type')->ignore($id),
            ],
        ]);

        // Update Source fields
        $lock_type->update([
            'type' => $validated['type'],
        ]);

        return response()->json([
            'class' => 'bg-success',
            'error' => false,
            'message' => 'Payment Type Updated Successfully',
            'call_back' => '',
            'table_referesh' => true,
            'model_id' => 'dataSave'
        ]);
    }



    public function destroy($id){
        $lock_type = DyeLockType::findOrFail($id);
        $lock_type->delete();

        return response()->json(['message'=>'Payment Type deleted Successfully ...', 'class'=>'success']); 
    }
}
