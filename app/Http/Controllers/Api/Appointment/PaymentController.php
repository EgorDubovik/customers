<?php

namespace App\Http\Controllers\Api\Appointment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Payment;
use Illuminate\Support\Str;
class PaymentController extends Controller
{
    public function store(Request $request, $appointment_id){
        $appointment = Appointment::find($appointment_id);
        if(!$appointment)
            return response()->json(['error' => 'Appointment not found'], 404);
        
        $this->authorize('update-remove-appointment', $appointment);
        
        $request->validate([
            'amount' => 'required|numeric',
        ]);

        $paymentType = 0;
        foreach(Payment::TYPE as $key => $type){
            if(Str::lower($type) == Str::lower($request->payment_type)){
                $paymentType = $key + 1;
                break;
            }
        }
        
        $payment = $appointment->payments()->create([
            'amount' => $request->amount,
            'payment_type' => $paymentType,
            'company_id' => $request->user()->company_id,
        ]);
        
        return response()->json(['payment' => $payment], 200);
    }
}
