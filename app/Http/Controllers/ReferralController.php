<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ReferalCustomerStat;
use App\Models\ReferalLinksCode;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;

class ReferralController extends Controller
{
    public function index(Request $request, $code){
        $referalCode = ReferalLinksCode::where('code',$code)->first();
        if(!$referalCode)
            return abort(404);

        $customer = Customer::find($referalCode->customer_id);
        if(!$customer)
            return abort(404);

        ReferalCustomerStat::firstOrCreate([
            'company_id' => $referalCode->company_id,
            'customer_id' => $referalCode->customer_id,
            'ip' => $request->ip(),
        ]);

        return redirect('https://edservicetx.com/?ref='.$code);
    }

    public function stat(Request $request,$code){
        $referalCode = ReferalLinksCode::where('code',$code)->first();
        if(!$referalCode)
            return abort(404);
        $company = Auth::user()->company;
        $stats = ReferalCustomerStat::where('company_id',$company->id)
                                        ->where('customer_id',$referalCode->customer_id)
                                        ->get();
        
        return view('referral.stat',['stats' => $stats,'company'=>$company]);
    }
}
