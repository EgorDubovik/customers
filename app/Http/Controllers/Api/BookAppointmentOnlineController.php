<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BookAppointment;

class BookAppointmentOnlineController extends Controller
{
    public function index($key){
        $companyBook = BookAppointment::where('key',$key)
                                    ->where('active',1)
                                    ->first();
        if(!$companyBook)
            return response()->json(['error' => 'Company not found'],404);
        
        $company = $companyBook->company;
        if(!$company)
            return response()->json(['error' => 'Company not found'],404);

        $returnCompanyJSON = [
            'key' => $key,
            'logo' =>'https://edservice.s3.us-east-2.amazonaws.com/'.$company->logo,
            'phone' => $company->phone,
            'name' => $company->name,
            'services' => $company->services,
        ];
        
        return response()->json(['company' => $returnCompanyJSON,'key' => $key],200);
    }
}
