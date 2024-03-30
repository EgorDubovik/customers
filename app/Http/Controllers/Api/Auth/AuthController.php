<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request){
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
            $user = Auth::user();
            $user->tokens()->delete();
            $success['token'] =  $user->createToken('API token')->plainTextToken;
            return response()->json(['success' => $success], 200); 
        } 
        
        return response()->json(['message'=>'Invalid username or password'], 401);  
    }
}
