<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Appointment;
use App\Models\Service;
use App\Models\AppointmentService;
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
        $data = [];
        $services = Service::where('company_id',Auth::user()->company_id)
            ->get();
        $data['services'] = $services;
        if ($request->has("customer")){
            $customer = Customer::where(['id' => $request->customer, 'company_id' => Auth::user()->company_id])->first();
            if ($customer)
                $data['customer'] = $customer;
        }

        $customers = Customer::where('company_id',Auth::user()->company_id)
            ->get();
        $data['customers'] = $customers;

        return view('schedule.create',$data);
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
        ]);

        // check if this is my customer
        $appointment = Appointment::create([
            'customer_id' => $request->customer,
            'company_id' => Auth::user()->company_id,
            'start' => $request->time_from,
            'end' => $request->time_to,
        ]);
        
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
    public function show(Appointment $Appointment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Appointment  $Appointment
     * @return \Illuminate\Http\Response
     */
    public function edit(Appointment $Appointment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Appointment  $Appointment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Appointment $Appointment)
    {
        //
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
}
