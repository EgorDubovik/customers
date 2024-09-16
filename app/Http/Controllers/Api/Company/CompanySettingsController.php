<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CompanySettings\GeneralInfoSettings;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;
class CompanySettingsController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('change-company-settings');
        $companySettings = GeneralInfoSettings::getSettingsForCompany($request->user()->company_id);
        $companySettings['companyName'] = $request->user()->company->name;
        $companySettings['companyEmail'] = $request->user()->company->email;
        $companySettings['companyPhone'] = $request->user()->company->phone;
        $companySettings['companyAddress'] = $request->user()->company->address;
        $companySettings['companyLogo'] = $request->user()->company->logo;
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


    public function uploadLogo(Request $request)
    {
        $company = Auth::user()->company;
        Gate::authorize('edit-company',['company'=> $company]);
        $request->validate([
            'logo' => 'required|image|mimes:jpeg,png,jpg'
        ]);

        $filePath = 'logos/'.time() . '_' . $request->file('logo')->hashName();
        
        $image = Image::make($request->file('logo'));
        
        if ($image->width() > env('UPLOAD_WIDTH_SIZE'))
        
            $image->resize(env('UPLOAD_WIDTH_SIZE'), env('UPLOAD_WIDTH_SIZE'), function ($constraint) {
                $constraint->aspectRatio();
            });
            
        $image = $image->encode();
        $path = Storage::disk('s3')->put($filePath, $image);
        if(!$path)
            return response()->json(['message' => 'Error uploading image'], 500);
        
        if($company->logo){
            Storage::disk('s3')->delete($company->logo);
        }
        
        $company->logo = env('AWS_FILE_ACCESS_URL').$filePath;
        $company->save();
        return response()->json(['message' => 'Logo updated', 'newPath'=> env('AWS_FILE_ACCESS_URL').$filePath], 200);
    }
}
