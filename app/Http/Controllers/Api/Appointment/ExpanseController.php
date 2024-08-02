<?php

namespace App\Http\Controllers\Api\Appointment;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Expanse;
use Illuminate\Http\Request;

class ExpanseController extends Controller
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

        $expanse = Expanse::create([
            'title' => $request->title,
            'amount' => $request->amount,
            'user_id' => auth()->id(),
            'appointment_id' => $appointment->id,
            'company_id' => auth()->user()->company_id,
        ]);

        return response()->json(['expanse' => $expanse],200);

    }

    public function delete(Request $request, $appointment_id, $expanse_id){
        $appointment = Appointment::find($appointment_id);
        if(!$appointment)
            return response()->json(['error' => 'Appointment not found'],404);

        $expanse = Expanse::find($expanse_id);
        if(!$expanse)
            return response()->json(['error' => 'Expanse not found'],404);

        $this->authorize('update-remove-appointment',$appointment);

        $expanse->delete();

        return response()->json(['message' => 'Expanse deleted'],200);
    }
}
