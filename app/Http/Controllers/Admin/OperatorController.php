<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Operator\OperatorCollection;
use App\Models\Admin;
use App\Models\Operator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OperatorController extends Controller{

    public function index(Request $request){
        if ($request->wantsJson()) {
            $datas = Operator::orderBy('name','asc')->with(['module']);

            $name = request()->input('name');
            if ($name) {
                $datas->where('name', 'like', '%'.$name.'%');
            }

            $status = $request->input('status');
            if ($status) {
                $datas->where('status_id', $status);
            }

            $request->merge(['recordsTotal' => $datas->count(), 'length' => $request->length]);
            $datas = $datas->limit($request->length)->offset($request->start)->get();

            return response()->json(new OperatorCollection($datas));

        }
        return view('admin.operator.list');
    }



    public function create(Request $request ){
        return view('admin.operator.create');
    }


    public function store(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'login' => 'required',
            'module' => 'required',
            'status' => 'required|integer',
        ]);


        $operator = Operator::create([
            'name' => $request->name,
            'module_id' => $request->module,
            'admin_id' => $request->login,
            'status_id' => $request->status,
        ]);

        return response()->json(['class' => 'bg-success', 'error' => false, 'message' => 'Operator Saved Successfully', 'call_back' => '', 'table_refresh' => true, 'model_id' => 'dataSave']);
    }



    public function update(Request $request, $id){
        DB::transaction(function () use ($request, $id) {

            $request->validate([
                'name'   => 'required|string|max:255',
                'login'  => 'required',
                'module' => 'required',
                'status' => 'required|integer',
            ]);

            $operator = Operator::findOrFail($id);
            $admin    = Admin::findOrFail($request->login);

            if ($admin->listing_type === 'Own') {
                $count = Operator::where('admin_id', $admin->id)
                    ->where('id', '!=', $operator->id)
                    ->count();

                if ($count >= 1) {
                    throw new \Exception('This user is Own listing, only one operator is allowed.');
                }
            }

            $operator->update([
                'name'      => $request->name,
                'module_id' => $request->module,
                'admin_id'  => $request->login,
                'status_id' => $request->status,
            ]);
        });

        return response()->json([
            'error'         => false,
            'class'         => 'bg-success',
            'message'       => 'Operator Saved Successfully',
            'table_refresh' => true,
            'model_id'      => 'dataSave',
        ]);
    }


    public function edit($id){
        $operator = Operator::find($id);
        return view('admin.operator.edit', compact('operator'));
    }


}
