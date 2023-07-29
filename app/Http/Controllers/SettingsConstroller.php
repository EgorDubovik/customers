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

    public function savePaymentDepositType(Request $request){
        
        $validate = $request->validate([
            'payment_deposit_type' => 'required|numeric',
            'payment_deposit_type' => 'required|numeric',
            'payment_deposit_type' => 'required|numeric',
        ]);

        if($request->payment_deposit_amount_prc > 100 || $request->payment_deposit_amount_prc < 0)
            return back()->withErrors(['error'=>'Procent must be less then 100% or more then 0 %']);
        
        if($request->payment_deposit_amount < 0)
            return back()->withErrors(['error'=>'Deposit amount must be more 0']);
        
        if($request->payment_deposit_type <0 || $request->payment_deposit_type > 1)
            return back()->withErrors(['error'=>'Wrong deposit type']);

        $settings = Settings::where('company_id',Auth::user()->company_id)->first();
        $settings->payment_deposit_type = $request->payment_deposit_type;
        $settings->payment_deposit_amount = $request->payment_deposit_amount;
        $settings->payment_deposit_amount_prc = $request->payment_deposit_amount_prc;
        $settings->save();

        return back()->with('success', 'Deposit inforation have been updated successfull');
    }
}
