<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\AppointmentService;

class AppointmentServiceController extends Controller
{
    public function store(Request $request, Appointment $appointment)
    {   
        Gate::authorize('add-remove-service-from-appointment',[$appointment]);
        
        if($request->has('service-prices')){
            foreach($request->input('service-prices') as $key => $value){
                AppointmentService::create([
                    'appointment_id' => $appointment->id,
                    'title' => $request->input('service-title')[$key],
                    'description' => $request->input('service-description')[$key],
                    'price' => $request->input('service-prices')[$key],
                ]);
            }
        }

        return back();
    }
}
