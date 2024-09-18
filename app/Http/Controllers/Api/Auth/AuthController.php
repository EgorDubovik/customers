<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens; 
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Company;
use App\Models\Role;
use App\Models\Settings;
class AuthController extends Controller
{
    use HasApiTokens;

    
    public function login(Request $request){
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
            $user = Auth::user();
            $success['token'] =  $user->createToken('API token')->plainTextToken;
            return response()->json(['success' => $success], 200); 
        } 
        
        return response()->json(['error'=>'Invalid username or password'], 403);  
    }

    public function register(Request $request){
        $request->validate([
            'companyName' => 'required',
            'secretKey' => 'required',
            'userName' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ]);
        
        $company = Company::create([
            'name' => $request->companyName,
        ]);

        // Create first user for company
        $user = User::create([
            'name' => $request->userName,
            'company_id' => $company->id,
            'email' => $request->email,
            'password' => password_hash($request->password, PASSWORD_BCRYPT),
        ]);


        // Settings::create([
        //     'company_id' => $company->id,
        // ]);

        Role::create([
            'user_id' => $user->id,
            'role' => Role::ADMIN,
         ]);

        $success['token'] =  $user->createToken('API token')->plainTextToken;
        return response()->json(['success'=>$success], 200);
    }
}
