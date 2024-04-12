<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use App\Models\Payment;
use App\Models\Appointment;
use Carbon\Carbon;

class DashboardController extends Controller
{
   public function dashboard(Request $request)
   {
      $user = $request->user();

      // $customers_count = Customer::where('company_id', $user->company_id)->count();

      $currentMonth = Carbon::now()->startOfMonth();
      $currentWeek = Carbon::now()->startOfWeek();
      DB::statement("SET SQL_MODE=''");
      $paymentsCurentMonth = Payment::where('created_at', '>=', $currentMonth)
         ->where('company_id', $user->company_id)
         ->whereHas('appointment', function ($query) use ($user) {
            $query->whereHas('techs', function ($q) use ($user) {
               $q->where('tech_id', $user->id);
            });
         })
         ->get();
      $sumCurentMonth = $paymentsCurentMonth->sum('amount');

      $paymentsCurentWheek = Payment::where('created_at', '>=', $currentWeek)
         ->where('company_id', $user->company_id)
         ->whereHas('appointment', function ($query) use ($user) {
            $query->whereHas('techs', function ($q) use ($user) {
               $q->where('tech_id', $user->id);
            });
         })
         ->get();
      $sumCurentWeek = $paymentsCurentWheek->sum('amount');

      return response()->json([
         'sumCurentMonth' => $sumCurentMonth,
         'sumCurentWeek' => $sumCurentWeek,
      ], 200);

      $paymentsLast30Days = Payment::where('created_at', '>=', Carbon::now()->subDays(30))
         ->where('company_id', $user->company_id)
         ->groupBy('appointment_id')
         ->selectRaw('sum(amount) as sum')
         ->get();

      $sumLast30Days = number_format($paymentsLast30Days->sum('sum') / 100, 2);
      $avarageLast30Days = number_format(round(($paymentsLast30Days->sum('sum') / 100) / 30, 2), 2);

      $currentDate = Carbon::now()->format('Y-m-d');
      $paymentsCurentDay = Payment::whereDate('created_at', $currentDate)->get();
      $sumCurentDay = number_format($paymentsCurentDay->sum('amount'), 2);


      $appointments = Appointment::where('company_id', $user->company_id)->get();
   }
}
