<?php

namespace App\Http\Controllers\Api\Appointment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Payment;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\User;

class PaymentController extends Controller
{

    public function index(Request $request){

        $endDate = ($request->endDate) ? Carbon::parse($request->endDate) : Carbon::now();
        $startDate = ($request->startDate) ? Carbon::parse($request->startDate) : Carbon::now()->subDays(31);

        $paymentsRows = Payment::where('company_id', $request->user()->company_id)
            ->where('created_at', '>=', $startDate)
            ->where('created_at', '<=', $endDate)
            ->get()
            ->sortDesc();
        
        $payments = [];
        $techs_id = [];
        foreach($paymentsRows as $payment){
            $payments[] = [
                'id' => $payment->id,
                'amount' => $payment->amount,
                'appointment' => $payment->appointment ?? null,
                'customer' => $payment->appointment->customer ?? null,
                'date' => $payment->created_at,
                'tech_id' => $payment->appointment->techs[0] ?? null,
                'payment_type' => Payment::TYPE[$payment->payment_type - 1],
            ];
            if(isset($payment->appointment->techs[0]) && !in_array($payment->appointment->techs[0]->id,$techs_id))
                $techs_id[] = $payment->appointment->techs[0]->id;
        }

        $techs = [];
        foreach($techs_id as $tech_id){
            $tech = User::find($tech_id);
            if($tech){
                $tech->roles->pluck('role');
                $techs[] = $tech;
            }
        }

        return response()->json(['payments' => $payments, 'techs'=>$techs], 200);
    }

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
