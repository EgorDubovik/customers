<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class PaymentController extends Controller
{

    public function index(Request $request){

        $endDate = Carbon::now();
        $startDate = Carbon::now()->subDays(30);

        $paymentsForGraph = DB::table('payments')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(amount) as total'))
            ->whereBetween('created_at', [$startDate, $endDate->addDay()])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy(DB::raw('DATE(created_at)'))
            ->pluck('total', 'date')
            ->toArray();

        
        // $datesInRange = Carbon::parse($startDate)->daysUntil($endDate);
        $datesInRange = CarbonPeriod::create($startDate,$endDate);
        $resultArray = [];
        foreach ($datesInRange as $date) {
            $formattedDate = $date->toDateString();
            $total = (!isset($paymentsForGraph[$formattedDate])) ? 0 : $paymentsForGraph[$formattedDate]; 
            $resultArray[] = [
                'day' => $formattedDate,
                'total' => $total,
            ];
        }
        // dd($datesInRange->toArray());

        return view('payment.index',[
            'paymentForGraph' => $resultArray, 
        ]);
    }

    public function store(Request $request, Appointment $appointment){
        
        Gate::authorize('pay-service',['appointment' => $appointment]);
        
        if($request->amount > 0)
            Payment::create([
                'appointment_id' => $appointment->id,
                'amount' => $request->amount,
                'payment_type' => $request->payment_type,
            ]);

        return back();
    }
}
