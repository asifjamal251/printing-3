<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Models\Admin;
use App\Rules\ReCaptcha;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */


    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/admin/dashboard';



    public function showLoginForm()
    {
        return view('admin.auth.login');
    }



    public function login(Request $request){
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $remember_me = $request->has('remember_me') ? true : false;

        $admin = Admin::where('email', $request->email)->first();

        if ($admin && $admin->status_id == 15) {
            return redirect()->back()->with(['class'=>'bg-danger','message'=>'You are not an active person, please contact the Owner.']);
        }

        if ($this->guard()->attempt([
            'email' => $request->email,
            'password' => $request->password,
            'status_id' => 14
        ], $remember_me)) {

            $admin = Auth::guard('admin')->user();

            if ($admin->google2fa_enabled == 14) {
                //dd($admin);
                session(['admin_id' => $admin->id]);
                Auth::guard('admin')->logout();
                return redirect()->route('admin.2fa.verify');
            }

            return redirect()->route('admin.dashboard.index')->with(['class'=>'success','message'=>'Logedin Successfully.']);
        }

        return redirect()->back()->with(['class'=>'bg-danger','message'=>'These credentials do not match our records.']);

    }


    // public function login(Request $request)
    // { 
    //     $request->validate([
    //         'password' => ['required'],
    //         'email' => ['required', 'email'],
    //         //'g-recaptcha-response' => ['required', new ReCaptcha]
    //     ]);

    //     $remember_me = $request->has('remember_me') ? true : false;

    //     $check_status = Admin::where(['email' => $request->email, 'status_id' => 15])->first();
    //     if ($check_status) {
    //         return response()->json([
    //             'class' => 'bg-danger',
    //             'message' => 'You are not an active person, please contact the Owner.',
    //             'error' => true
    //         ]);
    //     }

    //     if ($this->guard()->attempt([
    //         'email' => $request->email, 
    //         'password' => $request->password,
    //         'status_id' => 14
    //     ], $remember_me)) {

    //         return response()->json([
    //             'class' => 'bg-success',
    //             'error' => false,
    //             'message' => 'Login Successfully'
    //         ]);
    //     }
    //     return response()->json([
    //         'class' => 'bg-danger',
    //         'message' => 'These credentials do not match our records.',
    //         'error' => true
    //     ]);
    // }



    public function show2FAVerificationForm(){
        return view('admin.auth.2fa_verify');
    }

    public function verify2FA(Request $request){
        $request->validate([
            'one_time_password' => 'required',
        ]);

        $admin = Admin::find(session('admin_id'));

        //dd($admin);

        $google2fa = app('pragmarx.google2fa');
        $valid = $google2fa->verifyKey($admin->google2fa_secret, $request->one_time_password);

        if ($valid) {
            Auth::guard('admin')->login($admin);
            session()->forget('admin_id');
            return redirect()->route('admin.dashboard.index');
        } else {
            return redirect()->back()->withErrors(['one_time_password' => 'Invalid OTP']);
        }
    }



    public function logout()
    {
        $this->guard()->logout();
        return redirect()->route('admin.login.form');
    }



    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('admin.guest', ['except' => 'logout']);
    // }


    protected function guard()
    {
        return Auth::guard('admin');
    }

}
