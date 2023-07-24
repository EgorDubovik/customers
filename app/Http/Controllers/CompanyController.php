<?php

namespace App\Http\Controllers;

use App\Models\Addresses;
use App\Models\Company;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function edit(Company $company){
        Gate::authorize('edit-company',['company' => $company]);
        
        return view('company.edit',['company' => $company]);
        //return redirect()->route]('profile');
    }

    public function update(Request $request, Company $company){
        Gate::authorize('edit-company',['company' => $company]);
        
        if ($company->address()->exists()) {
            $company->address->update([
                'line1' => ($request->line1) ? $request->line1 : "",
                'line2' => ($request->line2) ? $request->line2 : "",
                'city' => ($request->city) ? $request->city : "",
                'state' => ($request->state) ? $request->state : "",
                'zip' => ($request->zip) ? $request->zip : "",
            ]);
        } else {
          $address = Addresses::create([
              'line1' => ($request->line1) ? $request->line1 : "",
              'line2' => ($request->line2) ? $request->line2 : "",
              'city' => ($request->city) ? $request->city : "",
              'state' => ($request->state) ? $request->state : "",
              'zip' => ($request->zip) ? $request->zip : "",
              'customer_id' => 0,
          ]);
          $company->address_id = $address->id;
        }

        $company->update([
            'name' => $request->customer_name,
            'phone' => $request->customer_phone,
            'email' => $request->email,
        ]);
        return redirect()->route('profile')->with('success', 'Information has been updated successful');
    }
}
