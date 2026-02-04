<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;


class DashboardController extends Controller
{
    public function index(Request $request){

        return view('admin.dashboard');
    }
    
    
}
