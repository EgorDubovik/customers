<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index(Request $request, $id){
        $appointment = Appointment::find($id);

        if(!$appointment)
            return response()->json(['error' => 'Appointment not found'], 404);

        $this->authorize('view-appointment', $appointment);
        $techs = $appointment->techs->load('roles');
        $appointment->load('customer','services','address','notes','payments');
        $appointment->techs = $techs;

        return response()->json(['appointment' => $appointment], 200);
    }

    // Appointment Techs
    public function removeTech(Request $request, $appointment_id, $tech_id){
        $appointment = Appointment::find($appointment_id);
        if(!$appointment)
            return response()->json(['error' => 'Appointment not found'], 404);

        $this->authorize('add-tech-to-appointment', [$appointment, $tech_id]);

        $appointment->techs()->detach($tech_id);

        return response()->json(['message' => 'Tech removed from appointment'], 200);
    }

    public function addTech(Request $request, $appointment_id){
        $appointment = Appointment::find($appointment_id);
        if(!$appointment)
            return response()->json(['error' => 'Appointment not found'], 404);

        $appointment->techs()->detach();
        foreach($request->techs as $tech_id){
            $this->authorize('add-tech-to-appointment', [$appointment, $tech_id]);
            $appointment->techs()->attach($tech_id);
        }        

        return response()->json(['message' => 'Tech added to appointment'], 200);
    }
}
