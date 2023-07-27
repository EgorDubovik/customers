<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Settings;
use Illuminate\Support\Facades\Auth;

class SettingsConstroller extends Controller
{
    public function show(Request $request){

        $settings = Settings::where('company_id',Auth::user()->company_id)->first();
        if(!$settings){
            $settings = Settings::create([
                'company_id' => Auth::user()->company_id,
            ]);
        }
        return view('settings',['settings'=>$settings]);
    }
}
