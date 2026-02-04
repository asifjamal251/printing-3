<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Vendor\VendorCollection;
use App\Imports\VendorsImport;
use App\Models\City;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class VendorController extends Controller{

    public function index(Request $request){
        if ($request->wantsJson()) {
            $datas = Vendor::orderBy('company_name','asc')
            ->with(['media', 'city']);

            $name = request()->input('name');
            if ($name) {
                $datas->where('company_name', 'like', '%'.$name.'%');
            }

            $contact_no = request()->input('contact_no');
            if ($contact_no) {
                $datas->where('contact_no', 'like', '%'.$contact_no.'%');
            }

            $email = request()->input('email');
            if ($email) {
                $datas->where('email', 'like', '%'.$email.'%');
            }

            $gst = request()->input('gst');
            if ($gst) {
                $datas->where('gst', 'like', '%'.$gst.'%');
            }


            $city = $request->input('city');
            if ($city) {
                $datas->where('city_id', $city);
            }

            $status = $request->input('status');
            if ($status) {
                $datas->where('status_id', $status);
            }

            $request->merge(['recordsTotal' => $datas->count(), 'length' => $request->length]);
            $datas = $datas->limit($request->length)->offset($request->start)->get();

            return response()->json(new VendorCollection($datas));

        }
        return view('admin.vendor.list');
    }



    public function create(Request $request ){
        return view('admin.vendor.create');
    }


     public function store(Request $request){
        $request->validate([
            'company_name' => 'required|string|max:255',
            'email' => 'required|email|max:91',
            'contact_no' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:6',
            'media_id' => 'nullable|integer',
            'gst' => 'nullable|unique:vendors,gst|max:355',
            'status' => 'required|integer',
            'state' => 'required',
            'city' => 'required',
            'cc_emails' => 'nullable',
        ]);

        //$emails = $this->parseCcEmails($request->cc_emails);

        $vendor = Vendor::create([
            'company_name' => $request->company_name,
            'email' => $request->email,
            'contact_no' => $request->contact_no,
            'password' => Hash::make(123456),
            'state_id' => $request->state,
            'city_id' => $request->city,
            'pincode' => $request->pincode,
            'address' => $request->address,
            'gst' => $request->gst,
            'cc_emails' => $request->cc_emails,
            'status_id' => $request->status,
        ]);

        return response()->json(['class' => 'bg-success', 'error' => false, 'message' => 'Vendor Saved Successfully', 'call_back' => '', 'table_referesh' => true, 'model_id' => 'dataSave']);
    }



    public function update(Request $request, $id){
        $request->validate([
            'company_name' => 'required|string|max:255',
            'email' => 'required|email|max:91|unique:vendors,email,' . $id,
            'contact_no' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:6',
            'media_id' => 'nullable|integer',
            'gst' => 'nullable|string|max:255',
            'status' => 'required|integer',
            'state' => 'required',
            'city' => 'required',
        ]);

        //$emails = $this->parseCcEmails($request->cc_emails);

        $vendor = Vendor::findOrFail($id);

        $vendor->update([
            'company_name' => $request->company_name,
            'email' => $request->email,
            'contact_no' => $request->contact_no,
            //'password' => $request->filled(123456) ? Hash::make($request->password) : $vendor->password,
            'media_id' => $request->media_id,
            'state_id' => $request->state,
            'city_id' => $request->city,
            'pincode' => $request->pincode,
            'address' => $request->address,
            'gst' => $request->gst,
            'status_id' => $request->status,
            //'cc_emails' => !empty($emails) ? json_encode($emails) : null,
            'cc_emails' => $request->cc_emails,
        ]);

        return response()->json(['class' => 'bg-success', 'error' => false, 'message' => 'Vendor Saved Successfully', 'call_back' => '', 'table_referesh' => true, 'model_id' => 'dataSave']);
    }


    public function edit($id){
        $vendor = Vendor::find($id);
        return view('admin.vendor.edit', compact('vendor'));
    }


    public function importCreate(){
        return view('admin.vendor.import');
    }

    public function importStore(Request $request): JsonResponse{
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]);

        $import = new VendorsImport();
        Excel::import($import, $request->file('file'));

        if (!empty($import->errors)) {
            return response()->json([
                'class' => 'bg-danger',
                'error' => true,
                'message' => $import->errors,
                'validation_errors' => $import->errors,
                'call_back' => '',
                'table_refresh' => false,
                'model_id' => 'dataSave',
            ]);
        }

        return response()->json([
            'class' => 'bg-success',
            'error' => false,
            'message' => 'Vendors imported successfully.',
            'call_back' => '',
            'table_refresh' => true,
            'model_id' => 'dataSave',
        ]);
    }

}
