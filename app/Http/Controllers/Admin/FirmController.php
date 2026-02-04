<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Firm\FirmCollection;
use App\Models\City;
use App\Models\Firm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class FirmController extends Controller{

    public function index(Request $request){
        if ($request->wantsJson()) {
            $datas = Firm::orderBy('company_name','asc')
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

            return response()->json(new FirmCollection($datas));

        }
        return view('admin.firm.list');
    }



    public function create(Request $request ){
        return view('admin.firm.create');
    }


     public function store(Request $request){
        $request->validate([
            'company_name' => 'required|string|max:255',
            'email' => 'required|email|unique:firms,email|max:91',
            'contact_no' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:6',
            'media_id' => 'nullable|integer',
            'gst' => 'nullable|string|max:255',
            'status' => 'required|integer',
            'state' => 'required',
            'city' => 'required',
            'cc_emails' => 'nullable',
        ]);

        //$emails = $this->parseCcEmails($request->cc_emails);

        $firm = Firm::create([
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

        return response()->json(['class' => 'bg-success', 'error' => false, 'message' => 'Firm Saved Successfully', 'call_back' => '', 'table_referesh' => true, 'model_id' => 'dataSave']);
    }



    public function update(Request $request, $id){
        $request->validate([
            'company_name' => 'required|string|max:255',
            'email' => 'required|email|max:91|unique:firms,email,' . $id,
            'contact_no' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:6',
            'media_id' => 'nullable|integer',
            'gst' => 'nullable|string|max:255',
            'status' => 'required|integer',
            'state' => 'required',
            'city' => 'required',
        ]);

        //$emails = $this->parseCcEmails($request->cc_emails);

        $firm = Firm::findOrFail($id);

        $firm->update([
            'company_name' => $request->company_name,
            'email' => $request->email,
            'contact_no' => $request->contact_no,
            //'password' => $request->filled(123456) ? Hash::make($request->password) : $firm->password,
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

        return response()->json(['class' => 'bg-success', 'error' => false, 'message' => 'Firm Saved Successfully', 'call_back' => '', 'table_referesh' => true, 'model_id' => 'dataSave']);
    }


    public function edit($id){
        $firm = Firm::find($id);
        return view('admin.client.edit', compact('firm'));
    }

}
