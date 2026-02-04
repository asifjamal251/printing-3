<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DepartmentController extends Controller
{
    public function create(Request $request ){
        return view('admin.department.create');
    }



    public function store(Request $request){
        $validated = $request->validate([
            'name' => 'required|string|max:191|unique:departments,name',
        ]);

        // Create the Source
        $department = Department::create([
            'name' => $validated['name'],
        ]);


        return response()->json([
            'class' => 'bg-success',
            'error' => false,
            'message' => 'Department Saved Successfully',
            'call_back' => '',
            'table_referesh' => true,
            'model_id' => 'dataSave'
        ]);
    }




    public function edit($id){
        $department = Department::findOrFail($id);
        return view('admin.department.edit', compact('department'));
    }




    public function update(Request $request, $id){
        $department = Department::findOrFail($id);

        $validated = $request->validate([
            'type' => [
                'required',
                'string',
                'max:191',
                Rule::unique('departments', 'type')->ignore($id),
            ],
        ]);

        // Update Source fields
        $department->update([
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
        $department = Department::findOrFail($id);
        $department->delete();

        return response()->json(['message'=>'Payment Type deleted Successfully ...', 'class'=>'success']); 
    }
}
