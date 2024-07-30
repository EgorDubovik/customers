<?php

namespace App\Http\Controllers\Api\Appointment;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Expance;
use Illuminate\Http\Request;

class ExpanceController extends Controller
{
    public function store(Request $request, $appointment_id){
        $appointment = Appointment::find($appointment_id);
        if(!$appointment)
            return response()->json(['error' => 'Appointment not found'],404);

        $this->authorize('update-remove-appointment',$appointment);
        
        $request->validate([
            'title' => 'required',
            'amount' => 'required',
        ]);

        $expance = Expance::create([
            'title' => $request->title,
            'amount' => $request->amount,
            'user_id' => auth()->id(),
            'appointment_id' => $appointment->id,
            'company_id' => auth()->user()->company_id,
        ]);

        return response()->json(['expance' => $expance],200);

    }
}
