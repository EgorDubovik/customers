<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BookAppointment;
use Illuminate\Support\Facades\Auth;

class BookAppointmentController extends Controller
{
   function index()
   {
      $this->authorize('book-online');
      $bookAppointmentSettings = Auth::user()->company->bookAppointment;
      return response()->json(['settings' => $bookAppointmentSettings], 200);
   }

   function workingTime(Request $request)
   {
      $request->validate([
         'workingTime' => 'required|json',
      ]);

      $this->authorize('book-online');
      // Check if working time is valid json
      /*
            {
                monday: {
                    from: '08:00',
                    to: '17:00'
                },
                ...
            }
        */
      Auth::user()->company->bookAppointment->update(['working_time' => $request->workingTime]);

      return response()->json(['workingTime' => $request->workingTime], 200);
   }
}
