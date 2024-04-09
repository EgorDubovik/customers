<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens; 
use Illuminate\Support\Facades\Hash;
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
}
