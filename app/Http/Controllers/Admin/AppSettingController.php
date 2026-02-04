<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Project\ProjectCollection;
use App\Models\Country;
use App\Models\State;
use App\Models\AppSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PragmaRX\Countries\Package\Countries;

class AppSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $app_setting = AppSetting::with(['siteLogo', 'siteFavicon'])->latest()->first();
        return view('admin.app-setting.index',compact('app_setting'));
    }


    public function basicInfo(Request $request){
       $request->validate([
            'app_name' => 'required|string|max:200',
            'app_tag_line' => 'nullable|string|max:200',
            'app_description' => 'nullable|string|max:1200',
        ]);
       $app_setting = AppSetting::latest()->first();
       $app_setting->app_name = $request->app_name;
       $app_setting->app_tag_line = $request->app_tag_line;
       $app_setting->app_description = $request->app_description;
       if($request->input('logo')){
            foreach($request->logo as $file){
                $app_setting->logo = $file;
            } 
        }
        else{
            $app_setting->logo = NULL;
        }  

        if($request->input('favicon')){
            foreach($request->favicon as $file){
                $app_setting->favicon = $file;
            } 
        }
        else{
            $app_setting->favicon = NULL;
        }

        if($app_setting->save()){
            return response()->json([
                'class' => 'bg-success', 
                'error' => false, 
                'message' => 'App Basic Info Saved Successfully', 
                'call_back' => ''
            ]);
        }

       return response()->json([
            'class' => 'bg-danger', 
            'error' => false, 
            'message' => 'Something went wrong.', 
            'call_back' => ''
        ]);
       
    }


    public function contactDetails(Request $request){
       $request->validate([
            'owner_name' => 'required|string|max:200',
            // 'mobile_number' => 'required',
            // 'country' => 'required',
            // 'state' => 'required',
            // 'district' => 'required',
            // 'city' => 'required',
            // 'address' => 'required',
            // 'pincode' => 'required',
        ]);
       $app_setting = AppSetting::latest()->first();
       $app_setting->owner_name = $request->owner_name;
       $app_setting->mobile_number = $request->mobile_number;
       $app_setting->country_id = $request->country;
       $app_setting->state_id = $request->state;
       $app_setting->state_id = $request->state;
       $app_setting->district_id = $request->district;
       $app_setting->city_id = $request->city;
       $app_setting->address = $request->address;
       $app_setting->pincode = $request->pincode;
       if($app_setting->save()){
            return response()->json([
                'class' => 'bg-success', 
                'error' => false, 
                'message' => 'App Contact Details Saved Successfully', 
                'call_back' => ''
            ]);
       }

       return response()->json([
            'class' => 'bg-danger', 
            'error' => false, 
            'message' => 'Something went wrong.', 
            'call_back' => ''
        ]);
       
    }

    public function logo(Request $request)
    {
        //dd($request->all());
        $logo = AppSetting::latest()->first();
        $logo->title = $request->title;
        $logo->description = $request->description;
        $logo->email = $request->email;
        $logo->contact_no = $request->contact_no;
        $logo->country = $request->country;
        $logo->state = $request->state;
        $logo->city = $request->city;
        $logo->address = $request->address;


        if($request->input('logo')){
            foreach($request->logo as $file){
                $logo->logo = $file;
            } 
        }
        else{
            $logo->logo = Null;
        }  

        if($request->input('favicon')){
            foreach($request->favicon as $file){
                $logo->favicon = $file;
            } 
        }
        else{
            $logo->favicon = Null;
        }


       if($logo->save()){ 
            return redirect()->route('admin.site-setting.index')->with(['class'=>'success','message'=>'Site Information Save Successfully.']);
        }
        return redirect()->back()->with(['class'=>'error','message'=>'Whoops, looks like something went wrong ! Try again ...']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request )
    {
        $roles = Role::select(['id','name'])->get()->pluck('name','id')->toArray();
        return view('admin.project.create',compact('roles'));
    }

    public function getAllCountry()
    {
        $countries = new Countries();

       

        $all_countries = $countries->all();
        echo "<ul>";
        foreach($all_countries as $country){
             dd($countries->where('cca3', 'ESB'));

            $country_store = new Country;
            $country_store->name = $country->name->common;
            $country_store->iso_code = $country->cca3;
            $country_store->flag_emoji = $country->flag['emoji'];
            $country_store->flag_svg = $country->flag['svg'];
            $country_store->calling_code = $country->calling_codes?$country->calling_codes[0]:'N/A';
            

            $country_currency = $countries->where('name.common', $country->name->common)->first()->hydrateCurrencies()->currencies;

            $test = $countries->where('cca3', 'ATA')->first()->hydrateCurrencies()->currencies;

            $country_store->currency = $country->currencies[0];
            

            //dd($test->first()->count());


            $country_store->currency_sign = $country_currency->first()->count()>0?$country_currency->first()->units->major->symbol:'N/A';

            $country_store->save();

            $states = $countries->where('name.common', $country->name->common)
            ->first()
            ->hydrateStates()
            ->states
            ->sortBy('name')
            ->pluck('name', 'postal');

//echo $country->flag['svg'];
            // foreach($country->flag as $count_flag){
            //    echo "<li>".$count_flag ."</li>"; 
            // }
            
            // echo "<ul>";
            //     foreach($states as $state){
            //        echo "<li>".$state."</li>"; 
            //     }
            // echo "</li></ul>";

        }
        echo "</ul>";
    }


}
