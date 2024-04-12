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
      $paymentsCurrentMonth = Payment::where('created_at', '>=', $currentMonth)
         ->where('company_id', $user->company_id)
         ->whereHas('appointment', function ($query) use ($user) {
            $query->whereHas('techs', function ($q) use ($user) {
               $q->where('tech_id', $user->id);
            });
         })
         ->get();
      $sumCurrentMonth = $paymentsCurrentMonth->sum('amount');

      $paymentsCurrentWheek = Payment::where('created_at', '>=', $currentWeek)
         ->where('company_id', $user->company_id)
         ->whereHas('appointment', function ($query) use ($user) {
            $query->whereHas('techs', function ($q) use ($user) {
               $q->where('tech_id', $user->id);
            });
         })
         ->get();
      $sumCurrentWeek = $paymentsCurrentWheek->sum('amount');

      $currentDate = Carbon::now()->format('Y-m-d');
      $paymentsCurrentDay = Payment::whereDate('created_at', $currentDate)
         ->where('company_id', $user->company_id)
         ->whereHas('appointment', function ($query) use ($user) {
            $query->whereHas('techs', function ($q) use ($user) {
               $q->where('tech_id', $user->id);
            });
         })
         ->get();
      $sumCurrentDay = $paymentsCurrentDay->sum('amount');

      return response()->json([
         'sumCurrentMonth' => $sumCurrentMonth,
         'sumCurrentWeek' => $sumCurrentWeek,
         'sumCurrentDay' => $sumCurrentDay,
      ], 200);
   }
}
