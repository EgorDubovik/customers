<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BookAppointmentController extends Controller
{
    function workingTime(Request $request)
    {
        return response()->json(['workingTime' => 'status'], 200);
    }
}
