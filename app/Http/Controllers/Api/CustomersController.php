<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomersController extends Controller
{
    public function index(Request $request){
        $customers = Customer::where('company_id', Auth::user()->company->id)
            ->with('address')
            ->get()
            ->makeHidden(['address_id','company_id','created_at','updated_at']);

        
        return response()->json($customers);
    }
}
