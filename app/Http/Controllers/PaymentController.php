<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PaymentController extends Controller
{
    public function store(Request $request, Appointment $appointment){
        
        Gate::authorize('pay-service',['appointment' => $appointment]);


        // $totalAmount = $appointment->services()->sum('price');
        // $totalPaid = Payment::where('appointment_id', $appointment->id)->get()->sum('amount');
        // $remainigBalance = $totalAmount - $totalPaid;
        // if($remainigBalance > 0 && $remainigBalance == $request->amount)
        if($request->amount > 0)
            Payment::create([
                'appointment_id' => $appointment->id,
                'amount' => $request->amount,
                'payment_type' => $request->payment_type,
            ]);

        return back();
    }
}
