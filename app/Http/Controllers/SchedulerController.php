<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Scheduler;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SchedulerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('schedule.index');
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
        Scheduler::create([
            'customer_id' => $request->customer,
            'start' => $request->time_from,
            'end' => $request->time_to,
        ]);
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Scheduler  $scheduler
     * @return \Illuminate\Http\Response
     */
    public function show(Scheduler $scheduler)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Scheduler  $scheduler
     * @return \Illuminate\Http\Response
     */
    public function edit(Scheduler $scheduler)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Scheduler  $scheduler
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Scheduler $scheduler)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Scheduler  $scheduler
     * @return \Illuminate\Http\Response
     */
    public function destroy(Scheduler $scheduler)
    {
        //
    }
}
