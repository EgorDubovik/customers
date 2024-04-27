<?php

namespace App\Http\Controllers\Api\Appointment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Payment;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\User;
use Carbon\CarbonPeriod;

class PaymentController extends Controller
{

    public function index(Request $request)
    {

        $endDate = ($request->endDate) ? Carbon::parse($request->endDate) : Carbon::now();
        $startDate = ($request->startDate) ? Carbon::parse($request->startDate) : Carbon::now()->subDays(31);
        // $paymentsRows = Payment::where('company_id', $request->user()->company_id)
        //     ->where('created_at', '>=', $startDate)
        //     ->where('created_at', '<=', $endDate)
        //     ->get()
        //     ->sortDesc();
        $datesInRange = CarbonPeriod::create($startDate,$endDate->copy()->addDay());
        $paymentForGraph = [];
        $techs_id = [];
        foreach ($datesInRange as $date) {
            $formattedDate = $date->toDateString();
            $payments = Payment::where('company_id',$request->user()->company_id)
                // ->where('tech_id',$request->user()->id)
                ->whereDate('created_at', $formattedDate)
                ->with('appointment.customer')
                ->get(); 
            foreach($payments as $payment){
                $payment->payment_type = Payment::TYPE[$payment->payment_type - 1] ?? 'undefined';
                if (!in_array($payment->tech_id, $techs_id))
                    $techs_id[] = $payment->tech_id;
            }
            
            $paymentForGraph[] = [
                'payments' => $payments,
                'date' => $formattedDate,
            ];
        }

        // $payments = [];
        // 
        // $totalPerPeriod = 0;
        // $creditTransaction = 0;
        // $cashTransaction = 0;
        // $checkTransaction = 0;
        // $transferTransaction = 0;
        // foreach ($paymentsRows as $payment) {
        //     $payments[] = [
        //         'id' => $payment->id,
        //         'amount' => $payment->amount,
        //         'appointment' => $payment->appointment ?? null,
        //         'customer' => $payment->appointment->customer ?? null,
        //         'date' => $payment->created_at,
        //         'tech_id' => $payment->tech_id ?? null,
        //         'payment_type' => Payment::TYPE[$payment->payment_type - 1],
        //     ];
        //     $totalPerPeriod += $payment->amount;
        //     if ($payment->payment_type == Payment::CREDIT)
        //         $creditTransaction += $payment->amount;
        //     if ($payment->payment_type == Payment::CASH)
        //         $cashTransaction += $payment->amount;
        //     if ($payment->payment_type == Payment::CHECK)
        //         $checkTransaction += $payment->amount;
        //     if ($payment->payment_type == Payment::TRANSFER)
        //         $transferTransaction += $payment->amount;

        //     if (!in_array($payment->tech_id, $techs_id))
        //         $techs_id[] = $payment->tech_id;
        // }

        $techs = [];
        foreach ($techs_id as $tech_id) {
            $tech = User::find($tech_id);
            if ($tech) {
                $techs[] = $tech;
            }
        }

        // return response()->json([
        //     'payments' => $payments, 
        //     'techs' => $techs,
        //     'totalPerPeriod' => $totalPerPeriod,
        //     'creditTransaction' => $creditTransaction,
        //     'cashTransaction' => $cashTransaction,
        //     'checkTransaction' => $checkTransaction,
        //     'transferTransaction' => $transferTransaction,
        // ], 200);

        return response()->json([
            'paymentForGraph' => $paymentForGraph,
            'techs' => $techs,
        ], 200);
    }

    public function store(Request $request, $appointment_id)
    {
        $appointment = Appointment::find($appointment_id);
        if (!$appointment)
            return response()->json(['error' => 'Appointment not found'], 404);

        $this->authorize('update-remove-appointment', $appointment);

        $request->validate([
            'amount' => 'required|numeric',
        ]);

        $paymentType = 0;
        foreach (Payment::TYPE as $key => $type) {
            if (Str::lower($type) == Str::lower($request->payment_type)) {
                $paymentType = $key + 1;
                break;
            }
        }

        $payment = $appointment->payments()->create([
            'amount' => $request->amount,
            'payment_type' => $paymentType,
            'company_id' => $request->user()->company_id,
            'tech_id' => $request->user()->id,
        ]);

        return response()->json(['payment' => $payment], 200);
    }

    public function delete(Request $request, $payment_id)
    {
        $payment = Payment::find($payment_id);
        if (!$payment)
            return response()->json(['error' => 'Payment not found'], 404);

        $this->authorize('payment-remove', $payment);

        $payment->delete();

        return response()->json(['message' => 'Payment deleted'], 200);
    }
}
