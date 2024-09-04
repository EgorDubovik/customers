<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\AppointmentNotes;
use App\Models\Note;
use Illuminate\Http\Request;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\DeleteAppointment;
use App\Models\Role;

class AppointmentController extends Controller
{

    public function view(Request $request)
    {
        $appointments = Appointment::where('company_id', $request->user()->company_id)
            ->where(function ($query) use ($request) {
                if (!$request->user()->isRole([Role::ADMIN, Role::DISP]))
                    $query->whereHas('techs', function ($query) use ($request) {
                        $query->where('tech_id', $request->user()->id);
                    });
            })
            ->get();



        $returnAppointments = [];
        foreach ($appointments as $appointment) {

            $returnAppointments[] = [
                'id' => $appointment->id,
                'start' => $appointment->start,
                'end' => $appointment->end,
                'title' => $appointment->job->customer->name,
                'status' => $appointment->status,
                'bg' => $appointment->techs->first()->color ?? '#1565c0',
                
            ];
        }

        return response()->json(['appointments' => $returnAppointments], 200);
    }

    public function index(Request $request, $id)
    {
        $appointment = Appointment::find($id);

        if (!$appointment)
            return response()->json(['error' => 'Appointment not found'], 404);

        $this->authorize('view-appointment', $appointment);

        $appointment->title = $appointment->job->customer->name;
        $appointment->backgroundColor = $appointment->techs->first()->color ?? '#1565c0';
        $appointment->customer = $appointment->job->customer;
        $appointment->address = $appointment->job->address->full;
        $appointment->techs = $appointment->techs->load('roles');
        $appointment->notes = $appointment->job->notes()
            ->with(['creator:id,name'])
            ->orderBy('created_at', 'desc') // Sort by created_at in ascending order
            ->get(['id', 'text', 'updated_at', 'creator_id'])
            ->map(function ($note) {
                return [
                    'id' => $note->id,
                    'text' => $note->text,
                    'updated_at' => $note->updated_at,
                    'creator' => [
                        'id' => $note->creator->id,
                        'name' => $note->creator->name,
                    ],
                ];
            });
        $appointment->expenses = $appointment->job->expenses;
        $appointment->services = $appointment->job->services()->get(['id', 'title', 'description', 'price', 'taxable']);
        return response()->json(['appointment' => $appointment], 200);
    }



    public function store(Request $request)
    {
        $validate = $request->validate([
            'customerId'   => 'required|integer',
            'addressId'    => 'required|integer',
            'timeFrom'     => 'required',
            'timeTo'       => 'required',
        ]);

        $this->authorize('make-appointment', [$request->customerId, $request->addressId]);

        DB::beginTransaction();
        try {
            $startTime = Carbon::parse($request->timeFrom)->setSecond(0);
            $endTime = Carbon::parse($request->timeTo)->setSecond(0);
            $appointment = Appointment::create([
                'company_id' => $request->user()->company_id,
                'customer_id' => $request->customerId,
                'address_id' => $request->addressId,
                'start' => $startTime,
                'end' => $endTime,
                'status' => 0,
            ]);

            // add techs to appointment
            foreach ($request->techs as $tech) {
                $appointment->techs()->attach($tech);
            }

            // Add services to appointment
            if ($request->has('services')) {
                foreach ($request->services as $service) {
                    $appointment->services()->create([
                        'title' => $service['title'],
                        'description' => $service['description'],
                        'price' => $service['price'],
                        'taxable' => $service['taxable'],
                    ]);
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error creating appointment'], 500);
        }

        return response()->json(['message' => 'Appointment created', 'appointment' => $appointment], 200);
    }

    public function update(Request $request, $id)
    {
        $appointment = Appointment::find($id);
        if (!$appointment)
            return response()->json(['error' => 'Appointment not found'], 404);

        $this->authorize('update-remove-appointment', $appointment);

        if ($request->has('timeFrom'))
            $appointment->start = Carbon::parse($request->timeFrom);
        if ($request->has('timeTo'))
            $appointment->end = Carbon::parse($request->timeTo);

        $appointment->save();

        return response()->json(['message' => 'Appointment updated'], 200);
    }

    public function delete(Request $request, $id)
    {
        $appointment = Appointment::find($id);
        if (!$appointment)
            return response()->json(['error' => 'Appointment not found'], 404);

        $this->authorize('update-remove-appointment', $appointment);

        foreach ($appointment->techs as $tech) {
            Mail::to($tech->email)->send(new DeleteAppointment($appointment));
        }
        $appointment->techs()->detach();
        $appointment->services()->delete();
        $appointment->notes()->delete();
        $appointment->delete();

        return response()->json(['message' => 'Appointment deleted'], 200);
    }

    // Appointment status
    public function updateStatus(Request $request, $id)
    {
        $appointment = $this->isValidAppointment($id);

        $this->authorize('update-remove-appointment', $appointment);

        $appointment->update([
            'status' => !$appointment->status,
        ]);

        return response()->json(['message' => 'Appointment status updated'], 200);
    }

    // Appointment Techs
    public function removeTech(Request $request, $appointment_id, $tech_id)
    {
        $appointment = Appointment::find($appointment_id);
        if (!$appointment)
            return response()->json(['error' => 'Appointment not found'], 404);

        $this->authorize('add-tech-to-appointment', [$appointment, $tech_id]);

        $appointment->techs()->detach($tech_id);

        return response()->json(['message' => 'Tech removed from appointment'], 200);
    }

    public function addTech(Request $request, $appointment_id)
    {
        $appointment = Appointment::find($appointment_id);
        if (!$appointment)
            return response()->json(['error' => 'Appointment not found'], 404);

        $appointment->techs()->detach();
        foreach ($request->techs as $tech_id) {
            $this->authorize('add-tech-to-appointment', [$appointment, $tech_id]);
            $appointment->techs()->attach($tech_id);
        }

        return response()->json(['message' => 'Tech added to appointment'], 200);
    }

    // Appointment services

    public function addService(Request $request, $appointment_id)
    {
        $appointment = $this->isValidAppointment($appointment_id);

        $this->authorize('add-remove-service-from-appointment', $appointment);

        $service = $appointment->services()->create([
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'taxable' => $request->taxable,
        ]);

        return response()->json(['message' => 'Service added to appointment', 'service' => $service], 200);
    }

    public function removeService(Request $request, $appointment_id, $service_id)
    {
        $appointment = $this->isValidAppointment($appointment_id);

        $this->authorize('add-remove-service-from-appointment', $appointment);
        $appointment->services()->where('id', $service_id)->delete();

        return response()->json(['message' => 'Service removed from appointment'], 200);
    }

    public function updateService(Request $request, $appointment_id, $service_id)
    {
        $appointment = $this->isValidAppointment($appointment_id);

        $this->authorize('add-remove-service-from-appointment', $appointment);

        $service = $appointment->services()->where('id', $service_id)->first();
        if (!$service)
            return response()->json(['error' => 'Service not found'], 404);

        $service->update([
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'taxable' => $request->taxable,
        ]);

        return response()->json(['message' => 'Service updated'], 200);
    }

    private function isValidAppointment($appointment_id)
    {
        $appointment = Appointment::find($appointment_id);
        if (!$appointment)
            return response()->json(['error' => 'Appointment not found'], 404);
        return $appointment;
    }
}
