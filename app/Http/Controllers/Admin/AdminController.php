<?php

namespace App\Http\Controllers\Admin;

use App\Events\AdminEvent;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Admin\AdminCollection;
use App\Models\Admin;
use App\Models\AdminIp;
use App\Models\City;
use App\Models\Operator;
use App\Models\Role;
use App\Models\Wallet;
use App\Rules\GSTNumber;
use App\Rules\MobileNumber;
use Auth;
use Carbon\Carbon;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            $datas = Admin::orderBy('id','asc')->whereNotIn('role_id', [1])
            ->with(['role', 'media']);

            $name = request()->input('name');
            if ($name) {
                $datas->where('name', 'like', '%'.$name.'%');
            }

            $email = request()->input('email');
            if ($email) {
                $datas->where('email', 'like', '%'.$email.'%');
            }


            $role = $request->input('role');
            if ($role) {
                $datas->where('role_id', $role);
            }

            $status = $request->input('status');
            if ($status) {
                $datas->where('status_id', $status);
            }

            $request->merge(['recordsTotal' => $datas->count(), 'length' => $request->length]);
            $datas = $datas->limit($request->length)->offset($request->start)->get();

            return response()->json(new AdminCollection($datas));

        }
        return view('admin.admin.list');
    }



    public function create(Request $request )
    {
        $roles = Role::whereNotIn('id',[1])->select(['id','name'])->get()->pluck('name','id')->toArray();
        return view('admin.admin.create',compact('roles'));
    }



    public function show(Request $request, admin $admin )
    {
        $loginTimes = adminLogin::where('admin_id',$admin->id)->get();
        $policy = DB::table('role_policies')->where('role_id',$admin->role_id)->first();
        return view('admin.admin.view',compact('admin','loginTimes','policy'));
    }

    public function store(Request $request){
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/',
            ],
            'status' => 'required',
            'listing_type' => 'required',
            'role' => 'required',
            'email' => 'required|email|max:255|unique:admins',
            'mobile_number' => ['nullable', new MobileNumber()],
            'login_time_restriction_enabled' => 'required',
            'login_allowed_from' => 'nullable|required_if:login_time_restriction_enabled,14',
            'login_allowed_to'   => 'nullable|required_if:login_time_restriction_enabled,14',
        ]);

        try {

            $validIps = [];

            if ($request->ip_enabled == 14 && $request->ip_addresses) {
                foreach ($request->ip_addresses as $ip) {
                    if (!filter_var($ip, FILTER_VALIDATE_IP)) {
                        throw new \Exception("Invalid IP Address: $ip");
                    }
                    $validIps[] = $ip;
                }
            }

            $admin = new Admin;
            $admin->gender = $request->gender;
            $admin->role_id = $request->role;
            $admin->password = bcrypt($request->password);
            $admin->plain_password = $request->password;
            $admin->listing_type = $request->listing_type;
            $admin->name = $request->name;
            $admin->email = $request->email;
            $admin->mobile = $request->mobile_number;
            $admin->status_id = $request->status;
            $admin->ip_enabled = $request->ip_enabled;
            $admin->google2fa_enabled = $request->enabled_2fa;
            $admin->login_time_restriction_enabled = $request->login_time_restriction_enabled;
            $admin->login_allowed_from = $request->login_allowed_from;
            $admin->login_allowed_to  = $request->login_allowed_to;

            $admin->save();
            $admin->stores()->sync($request->stores);

            foreach ($validIps as $ip) {
                AdminIp::create([
                    'admin_id' => $admin->id,
                    'ip_address' => $ip,
                ]);
            }

            if ($request->enabled_2fa == 14) {
                return response()->json([
                    'class' => 'bg-success', 
                    'error' => false, 
                    'message' => 'Admin Saved Successfully', 
                    'call_back' => route('admin.admin.2fa.setup', $admin->id), 
                    'table_refresh' => true, 
                    'model_id' => 'dataSave'
                ]);
            }

            return response()->json([
                'class' => 'bg-success',
                'error' => false,
                'message' => 'Admin Saved Successfully',
                'call_back' => route('admin.admin.index'), 
                'table_referesh' => true,
            ]);


        } catch (\Exception $e) {

            return redirect()->back()->with([
                'class'   => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }




    public function edit(Request $request, $id ){
        $admin = Admin::where('id', $id)->first();
        return view('admin.admin.edit',compact('admin'));
    }






    public function update(Request $request, $id){
        $admin = Admin::findOrFail($id);

        $rules = [
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|max:255|unique:admins,email,' . $admin->id,
            'password'    => [
                'nullable',
                'string',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/',
            ],
            'status'      => 'required',
            'role'        => 'required',
            'enabled_2fa' => 'required|in:14,15',
            'ip_enabled'  => 'required|in:14,15',
            'ip_addresses'=> 'nullable|array',
            'login_time_restriction_enabled' => 'required',
            'login_allowed_from' => 'nullable|required_if:login_time_restriction_enabled,14',
            'login_allowed_to'   => 'nullable|required_if:login_time_restriction_enabled,14',
        ];

        $this->validate($request, $rules);

        try {
            return DB::transaction(function () use ($request, $admin) {

                $data = [
                    'listing_type'       => $request->listing_type,
                    'role_id'            => $request->role,
                    'name'               => $request->name,
                    'email'              => $request->email,
                    'status_id'          => $request->status,
                    'ip_enabled'         => $request->ip_enabled,
                    'google2fa_enabled'  => $request->enabled_2fa,
                    'login_time_restriction_enabled' => $request->login_time_restriction_enabled,
                    'login_allowed_from' => $request->login_allowed_from,
                    'login_allowed_to'  => $request->login_allowed_to,
                ];

                if ($request->filled('password')) {
                    $data['password'] = bcrypt($request->password);
                    $data['plain_password'] = $request->password;
                }

                $admin->update($data);
                $admin->stores()->sync($request->stores);

                if ($admin->listing_type === 'Own') {
                    $operators = Operator::where('admin_id', $admin->id)->get();
                    if ($operators->count() > 1) {
                        $operatorIds = $operators->pluck('id');
                        Operator::whereIn('id', $operatorIds)->update(['status_id' => 15]);
                    }
                }

                if ((int) $request->ip_enabled === 14) {

                    $validIps = [];

                    if ($request->filled('ip_addresses')) {
                        foreach ($request->ip_addresses as $ip) {
                            if (!filter_var($ip, FILTER_VALIDATE_IP)) {
                                throw new \Exception("Invalid IP Address: {$ip}");
                            }
                            $validIps[] = $ip;
                        }
                    }

                    AdminIp::where('admin_id', $admin->id)
                    ->whereNotIn('ip_address', $validIps)
                    ->delete();

                    foreach ($validIps as $ip) {
                        AdminIp::firstOrCreate([
                            'admin_id'   => $admin->id,
                            'ip_address' => $ip,
                        ]);
                    }
                }


                if ((int) $request->enabled_2fa === 14 && !$admin->google2fa_secret) {
                    return response()->json([
                        'class' => 'bg-warning',
                        'error' => false,
                        'message' => 'Please setup 2FA first',
                        'call_back' => route('admin.admin.2fa.setup', $admin->id),
                        'table_refresh' => false,
                    ]);
                }




                return response()->json([
                    'class' => 'bg-success',
                    'error' => false,
                    'message' => 'Admin Updated Successfully',
                    'call_back' => route('admin.admin.index'),
                    'table_refresh' => true,
                ]);
            });

        } catch (\Exception $e) {
            return response()->json([
                'class' => 'bg-danger',
                'error' => true,
                'message' => $e->getMessage(),
                'table_refresh' => false,
            ]);
        }
    }







    public function profileUpdate(Request $request) {


        $this->validate($request,[
            'name'=>'required',
        ]);

        $admin = Auth::guard('admin')->user();

        $admin->name = $request->name;
        $admin->mobile = $request->mobile_no;
        $admin->gender = $request->gender;
        $admin->state = $request->state;
        $admin->city = $request->city;
        $admin->pincode = $request->zipcode;
        $admin->address = $request->address;
        $admin->bio = $request->bio;
        $admin->date_of_birth = Carbon::parse($request->date_of_birth)->format('Y-m-d');


        if($admin->save()){
            return response()->json(['message'=>'Profile  Updated', 'class'=>'success']);
        }

        return response()->json(['message'=>'Whoops, looks like something went wrong ! Try again ...', 'class'=>'error']);
    }



    public function destroy(Request $request, Admin $admin)
    {

        if($admin->delete()){

            return response()->json(['message'=>'User deleted successfully ...', 'class'=>'success', 'error'=>false, 'title'=>'Item Deleted!', 'timer'=>2000]);

        }
        return response()->json(['message'=>'Whoops, looks like something went wrong ! Try again ...', 'class'=>'error']);
    }




    public function profilePhotoUpdate(Request $request, $id)
    {

        $request->validate([
            'avatar'=>'required',   
        ]);

        $admin = Auth::guard('admin')->user();

        if($request->hasFile('avatar')){
            $image_name = time().".".$request->file('avatar')->getClientOriginalExtension();
            $image = $request->file('avatar')->storeAs('media/admin', $image_name);
            $storage_type = env('FILESYSTEM_DISK');
            if($storage_type == 's3'){
                $admin->avatar = config('appsetting.media_url').$image;
            }else{
                $admin->avatar = 'storage/'.$image;
            }
            
        }

        if($admin->save()){ 
            return response()->json(['message'=>'Profile Photo Updated', 'class'=>'success']);
        }

        return response()->json(['message'=>'Whoops, looks like something went wrong ! Try again ...', 'class'=>'error']);
    }


    public function profileCoverPhotoUpdate(Request $request, $id)
    {

        $request->validate([
            'cover_photo'=>'required',   
        ]);

        $admin = Auth::guard('admin')->user();

        if($request->hasFile('cover_photo')){
            $image_name = time().".".$request->file('cover_photo')->getClientOriginalExtension();
            $image = $request->file('cover_photo')->storeAs('media/admin', $image_name);
            $storage_type = env('FILESYSTEM_DISK');
            if($storage_type == 's3'){
                $admin->cover_photo = config('appsetting.media_url').$image;
            }else{
                $admin->cover_photo = 'storage/'.$image;
            }
        }

        if($admin->save()){ 
            return response()->json(['message'=>'Profile Photo Updated', 'class'=>'success']);
        }

        return response()->json(['message'=>'Whoops, looks like something went wrong ! Try again ...', 'class'=>'error']);
    }






    public function updatePassword(Request $request)
    {

        $this->validate($request,[
            'current_password' => 'required|min:6',
            'new_password' => 'required|min:6|confirmed',

        ]);

        if(Hash::check($request->current_password, Auth::guard('admin')->user()->password)) {
            $admin = Auth::guard('admin')->user();
            $admin->password = bcrypt($request->new_password);
            if($admin->save()){
                return response()->json(['message'=>'Password changed successfully.', 'class'=>'success']);
                
            }
            return response()->json(['message'=>'Whoops, looks like something went wrong ! Try again ...', 'class'=>'error']);
            
        }
        return response()->json(['message'=>'Old Password is not match', 'class'=>'error']);
        
    }




    public function profile(Request $request)
    {

        $admin = Auth::guard('admin')->user();
        return view('admin.admin.profile', compact('admin'));
    }


    public function changePassword(Request $request, $id)
    {
        return view('admin.admin.change-password');
    }


    public function setup2FA($id){
        $admin = Admin::where('id', $id)->first();
        $google2fa = app('pragmarx.google2fa');
        $secretKey = $google2fa->generateSecretKey();

        $qrCodeUrl = $google2fa->getQRCodeInline(
            config('app.name'),
            $admin->email,
            $secretKey
        );

        return view('admin.admin.2fa_setup', ['secret' => $secretKey, 'qrCodeUrl' => $qrCodeUrl, 'id' => $id, 'admin' => $admin]);
    }

    public function enable2FA(Request $request, $id){
        $request->validate([
            'secret' => 'required',
            'one_time_password' => 'required',
        ]);

        $google2fa = app('pragmarx.google2fa');

        $valid = $google2fa->verifyKey($request->secret, $request->one_time_password);

        if ($valid) {
            $admin = Admin::where('id', $id)->first();
            $admin->google2fa_secret = $request->secret;
            $admin->google2fa_enabled = 14;
            $admin->status_id = 14;
            $admin->save();

            return redirect()->route('admin.admin.index')->with('status', '2FA enabled successfully.');
        } else {
            return redirect()->back()->withErrors(['one_time_password' => 'Invalid OTP']);
        }
    }



}
