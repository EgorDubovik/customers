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
         
        AppointmentService::create([
            'appointment_id' => $appointment->id,
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
        ]);
        return back();
    }

    public function delete(Request $request, AppointmentService $appointmentService) {
        
        Gate::authorize('add-remove-service-from-appointment',[$appointmentService->appointment]);

        $appointmentService->delete();
        return back();

    }
}
