<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\AppointmentNotes;
use App\Models\Note;
use Illuminate\Http\Request;
use App\Models\Payment;
class AppointmentController extends Controller
{
    public function index(Request $request, $id){
        $appointment = Appointment::find($id);

        if(!$appointment)
            return response()->json(['error' => 'Appointment not found'], 404);

        $this->authorize('view-appointment', $appointment);
        $techs = $appointment->techs->load('roles');
        $payments = $appointment->payments;
        foreach($payments as $payment){
            $payment->payment_type = Payment::TYPE[$payment->payment_type - 1] ?? 'undefined';
        }
        $appointment->load('customer','services','address','notes');

        return response()->json(['appointment' => $appointment], 200);
    }

    public function view(Request $request){
        $appointments = Appointment::where('company_id',$request->user()->company_id)
                                    ->with('customer')
                                    ->with('techs')
                                    ->get();
        $returnAppointments = [];
        foreach($appointments as $appointment){

            // Load appointments based on user role
            // $this->authorize('view-appointment', $appointment);

            $returnAppointments[] = [
                'id' => $appointment->id,
                'start' => $appointment->start,
                'end' => $appointment->end,
                'title' => $appointment->customer->name,
                'bg' => $appointment->techs->first()->color,
            ];
        }

        return response()->json(['appointments' => $returnAppointments], 200);
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

    // Appointment notes
    public function addNote(Request $request, $appointment_id){
        $appointment = Appointment::find($appointment_id);
        if(!$appointment)
            return response()->json(['error' => 'Appointment not found'], 404);

        $this->authorize('appointment-store-note', $appointment);

        $note = AppointmentNotes::create([
            'appointment_id' => $appointment->id,
            'creator_id'    => $request->user()->id,
            'text'          => $request->text,
        ]);

        return response()->json(['message' => 'Note added to appointment','note'=>$note], 200);
    }

    public function removeNote(Request $request, $appointment_id, $note_id){
        $appointment = Appointment::find($appointment_id);
        if(!$appointment)
            return response()->json(['error' => 'Appointment not found'], 404);

        $note = AppointmentNotes::find($note_id);
        if(!$note)
            return response()->json(['error' => 'Note not found'], 404);

        $this->authorize('appointment-store-note', $appointment);

        $note->delete();

        return response()->json(['message' => 'Note removed from appointment'], 200);
    }

    // Appointment services
    
    public function addService(Request $request, $appointment_id){
        $appointment = $this->isValidAppointment($appointment_id);

        $this->authorize('add-remove-service-from-appointment', $appointment);

        $service = $appointment->services()->create([
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'taxable' => $request->taxable,
        ]);

        return response()->json(['message' => 'Service added to appointment','service' => $service], 200);
    }
    
    public function removeService(Request $request, $appointment_id, $service_id){
        $appointment = $this->isValidAppointment($appointment_id);

        $this->authorize('add-remove-service-from-appointment', $appointment);
        $appointment->services()->where('id',$service_id)->delete();

        return response()->json(['message' => 'Service removed from appointment'], 200);
    }

    public function updateService(Request $request, $appointment_id, $service_id){
        $appointment = $this->isValidAppointment($appointment_id);

        $this->authorize('add-remove-service-from-appointment', $appointment);

        $service = $appointment->services()->where('id',$service_id)->first();
        if(!$service)
            return response()->json(['error' => 'Service not found'], 404);
        
        $service->update([
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'taxable' => $request->taxable,
        ]);

        return response()->json(['message' => 'Service updated'], 200);
    }

    private function isValidAppointment($appointment_id){
        $appointment = Appointment::find($appointment_id);
        if(!$appointment)
            return response()->json(['error' => 'Appointment not found'], 404);
        return $appointment;   
    }

}
