<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show(Request $request){
        $user = $request->user();
        $user->rolesArray = $user->roles->pluck('role');
        return response()->json(['user' => $user], 200);
    }
}
