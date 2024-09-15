<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CompanySettings\GeneralInfoSettings;

class CompanySettingsController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('change-company-settings');
        $companySettings = GeneralInfoSettings::getSettingsForCompany($request->user()->company_id);
        return response()->json(['companySettings' => $companySettings], 200);
    }

    public function update(Request $request)
    {
        $this->authorize('change-company-settings');
        foreach ($request->all() as $key => $value) {       
            if(array_key_exists($key, GeneralInfoSettings::$DEFAULT_SETTINGS)){
                GeneralInfoSettings::setSetting($request->user()->company_id, $key, $value);    
            }
        }

        return response()->json(['message' => 'Save success'], 200);
    }
}
