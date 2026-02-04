<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Status\StatusCollection;
use App\Models\City;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StatusController extends Controller{

    public function index(Request $request){
        if ($request->wantsJson()) {
            $datas = Status::orderBy('id','asc');

            $name = request()->input('name');
            if ($name) {
                $datas->where('name', 'like', '%'.$name.'%');
            }

            $request->merge(['recordsTotal' => $datas->count(), 'length' => $request->length]);
            $datas = $datas->limit($request->length)->offset($request->start)->get();

            return response()->json(new StatusCollection($datas));

        }
        return view('admin.status.list');
    }



    public function create(Request $request ){
        return view('admin.status.create');
    }


     
    public function store(Request $request){
    //return $request->all();
        $request->validate([
            'name' => 'required|string|max:100',
            'background_colour' => 'required|string|max:100',
            'text_colour' => 'required|string|max:100',
        ]);

        DB::beginTransaction();

        try {
            $status = Status::create([
                'name' => $request->name,
                'background_colour' => $request->background_colour,
                'text_colour' => $request->text_colour,
            ]);

            DB::commit();

            return response()->json([
                'class' => 'bg-success',
                'error' => false,
                'message' => 'Status Saved Successfully',
                'call_back' => '',
                'table_referesh' => true,
                'model_id' => 'dataSave'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'class' => 'bg-success',
                'error' => false,
                'message' => $e,
                'call_back' => '',
                'table_referesh' => true,
                'model_id' => 'dataSave'
            ]);
        }
    }



    public function update(Request $request, $id){
        $request->validate([
            'name' => 'required|string|max:100',
            'background_colour' => 'required|string|max:100',
            'text_colour' => 'required|string|max:100',
        ]);

        DB::beginTransaction();

        try {
            $status = Status::findOrFail($id);

            $status->update([
                'name' => $request->name,
                'background_colour' => $request->background_colour,
                'text_colour' => $request->text_colour,
            ]);

            DB::commit();

            return response()->json([
                'class' => 'bg-success',
                'error' => false,
                'message' => 'Status Updated Successfully',
                'call_back' => '',
                'table_referesh' => true,
                'model_id' => 'dataSave'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'class' => 'bg-success',
                'error' => false,
                'message' => $e,
                'call_back' => '',
                'table_referesh' => true,
                'model_id' => 'dataSave'
            ]);
        }
    }


    public function edit($id){
        $status = Status::find($id);
        return view('admin.status.edit', compact('status'));
    }

}
