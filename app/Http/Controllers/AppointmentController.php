<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Appointment;
use App\Models\Service;
use App\Models\AppointmentService;
use App\Models\AppointmentTechs;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $appoinments = Appointment::where('company_id', Auth::user()->company_id)->get();
        
        return view('schedule.index', ['appointments' => $appoinments]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

        if ($request->has("customer")){
            $customer = Customer::where(['id' => $request->customer, 'company_id' => Auth::user()->company_id])->first();
            if ($customer)
                return view('schedule.create',['customer'=>$customer]);
        }

        return view('schedule.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'customer'        => 'required|integer',
            'time_from' => 'required',
            'time_to' => 'required',
            'tech_ids' => 'required',
        ]);

        // check if this is my customer
        $appointment = Appointment::create([
            'customer_id' => $request->customer,
            'company_id' => Auth::user()->company_id,
            'start' => $request->time_from,
            'end' => $request->time_to,
        ]);

        if($request->has('tech_ids')){
            foreach($request->tech_ids as $tech){
                AppointmentTechs::create([
                    'appointment_id' => $appointment->id,
                    'tech_id'        => $tech,
                    'creator_id'     => Auth::user()->id,
                ]);
            }
        } else 
            return redirect()->back()->withErrors(['msg' => 'Please choose at least one tech']);
        
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

        return redirect()->route('schedule.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Appointment  $Appointment
     * @return \Illuminate\Http\Response
     */
    public function show(Appointment $appointment)
    {

        $remainingBalance = $appointment->services->sum('price') - Payment::where('appointment_id',$appointment->id)->get()->sum('amount');

        return view('schedule.show',[
            'appointment'       => $appointment,
            'remainingBalance'   => $remainingBalance,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Appointment  $Appointment
     * @return \Illuminate\Http\Response
     */
    public function edit(Appointment $appointment)
    {   
        return  view('schedule.edit', ['appointment' => $appointment]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Appointment  $Appointment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Appointment $appointment)
    {
        $validate = $request->validate([
            'customer'        => 'required|integer',
            'time_from' => 'required',
            'time_to' => 'required',
            'tech_ids' => 'required',
        ]);

        // check if this is my customer
        $appointment->update([
            'start' => $request->time_from,
            'end' => $request->time_to,
            // 'tech_id' => $request->tech_id,
        ]);
        
        $appointment->appointmentTechs()->delete();

        if($request->has('tech_ids')){
            foreach($request->tech_ids as $tech){
                AppointmentTechs::create([
                    'appointment_id' => $appointment->id,
                    'tech_id'        => $tech,
                    'creator_id'     => Auth::user()->id,
                ]);
            }
        } else 
            return redirect()->back()->withErrors(['msg' => 'Please choose at least one tech']);

        $appointment->services()->delete();

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
        return redirect()->route('appointment.show',['appointment'=>$appointment]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Appointment  $Appointment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Appointment $Appointment)
    {
        //
    }

    public function removeTech(Request $request, Appointment $appointment, User $user){
        
        // Add gate !!!!!

        AppointmentTechs::where('appointment_id',$appointment->id)
            ->where('tech_id',$user->id)
            ->delete();
        return back();
    }

    public function viewall(Customer $customer)
    {   
        $appointments = Appointment::where('customer_id',$customer->id)->orderBy('end','desc')->get();
        return view('schedule.viewall',['customer'=>$customer,'appointments'=>$appointments]);
    }

    public function change_status(Appointment $appointment){
        // Add gate !!!!
        
        $appointment->status = ($appointment->status == Appointment::ACTIVE) ? Appointment::DONE : Appointment::ACTIVE;
        $appointment->save();
        return back();
    }

}
