<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingsConstroller extends Controller
{
    public function show(Request $request){

        return view('settings');
    }
}
