<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CompanySettings\GeneralInfoSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function show(Request $request){
        $user = $request->user();
        $user->rolesArray = $user->roles->pluck('role');
        $companySettings = GeneralInfoSettings::getSettingsForCompany($user->company_id);
        return response()->json(['user' => $user, 'companySettings'=>$companySettings], 200);
    }

    public function updatePassword(Request $request){
        $user = $request->user();
        $request->validate([
            'oldPassword' => 'required',
            'newPassword' => 'required',
        ]);
       
        if(!Hash::check($request->oldPassword, $user->password)){
            return response()->json(['message' => 'Old password is incorrect'], 400);
        }

        if($request->newPassword != $request->confirmPassword){
            return response()->json(['message' => 'New password and confirm password do not match'], 400);
        }
        $user->password = Hash::make($request->newPassword);
        $user->save();
        return response()->json(['message' => 'Password updated successfully'], 200);
    }
}
